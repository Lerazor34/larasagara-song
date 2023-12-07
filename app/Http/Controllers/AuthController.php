<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Files;
use App\Models\Priveleges;
use App\Models\RolePriveleges;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Services\ResponseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected $responder;
    protected $messages = [
        'required' => 'The :attribute field is required.',
        'unique'  => 'The :attribute field is unique.',
        'same'    => 'The :attribute and :other must match.',
        'size'    => 'The :attribute must be exactly :size.',
        'between' => 'The :attribute value :input is not between :min - :max.',
        'in'      => 'The :attribute must be one of the following types: :values',
    ];

    public function __construct(ResponseService $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request, User $model)
    {
        try {

            $rules = [
                'first_name' => 'required|string',
                'last_name' => 'string',
                'username' => 'required|string|unique:users',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|confirmed',
                'gender' => 'nullable|integer',
                'phone_number' => 'nullable|string',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->setStatus(400, 'Bad Request');
                $this->responder->set('message', $validator->errors()->first());
                return $this->responder->response();
            }

            $request->merge(['channel' => 'registration']);
            $fields = $request->only($model->getTableFields());
            foreach ($fields as $key => $value) {
                if ($key === 'password') {
                    $model->setAttribute($key, Hash::make($value));
                    continue;
                }
                $model->setAttribute($key, $value);
            }
            $model->save();

            $this->responder->set('message', Str::title('You\'re registered!'));
            $this->responder->set('data', $model);
            $this->responder->setStatus(201, 'Created.');
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->setErrors($e->getMessage(), 500);
            return $this->responder->response();
        }
    }


    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {

        try {
            $rules = [
                'username' => 'required',
                'password' => 'required|string',
                'remember_me' => 'boolean|nullable',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->setStatus(400, 'Bad Request');
                $this->responder->set('message', $validator->errors()->first());
                return $this->responder->response();
            }


            $credentials = request(['username', 'password']);
            $auth = Auth::attempt($credentials);
            if (!$auth) {
                $this->responder->setStatus(401, 'Unauthorized');
                $this->responder->set('message', 'Invalid User or Password.');
                return $this->responder->response();
            }

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->get('remember_me')) {
                $token->expires_at = Carbon::now()->addDays(30)->format('Y-m-d H:i:s');
                $token->save();
            }

            $roles = [];
            $permissions = [];
            if (isset($user['roles'])) {
                $user['role'] = array_map(function ($role) {
                    return $role['roles_id']['name'];
                }, $user['roles']->toArray());

                foreach ($user['roles']->toArray() as $role) {
                    $rolePriveleges = RolePriveleges::where('role', $role['id'])->get();
                    foreach ($rolePriveleges as $rolePrivelege) {
                        array_push($permissions, $rolePrivelege->toArray());
                    }
                }
            }
            $roles['permissions'] = $permissions;
            $roles['role'] = $role;

            $data = array(
                'user' => $user,
                'role' => $roles,
                'access_token' => $tokenResult->accessToken,
            );

            $this->responder->set('collection', 'User');
            $this->responder->set('message', 'You are Authorized');
            $this->responder->set('data', $data);
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->setErrors($e->getMessage(), 500);
            return $this->responder->responseFullData();
        }
    }

    /**
     * Refresh Token
     * 
     */

    public function refresh(Request $request)
    {
        try {
            if (Carbon::parse($request->user()->token()->expires_at)->greaterThanOrEqualTo(Carbon::now()->format('Y-m-d H:i:s'))) {
                $tokenResult = $request->user()->createToken('Personal Access Token');
                $data = array(
                    'access_token' => $tokenResult->accessToken,
                );
                $this->responder->setData($data);
                return $this->responder->response();
            }
            return $this->responder->setErrors('Token Expired', 403);
            return $this->responder->responseFullData();
        } catch (Exception $e) {
            $this->responder->setErrors($e->getMessage(), 500);
            return $this->responder->responseFullData();
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            $this->responder->set('collection', 'User');
            $this->responder->set('message', 'Successfully logged out');
            return $this->responder->response();
        } catch (\Exception $err) {
        }
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function profile(Request $request)
    {
        $user = Auth::user();

        $profile = User::with(['address', 'photo'])->find($user->id);
        $this->responder->set('collection', 'User');
        $this->responder->set('message', 'Data retrieved');
        $this->responder->set('data', $profile);
        return $this->responder->response();
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function updateProfile(Request $request)
    {
        try {
            $rules = [
                "email" => "required|email",
                "first_name" => "required|string",
                "last_name" => "required|string",
                "dob" => "required|date",
                "gender" => "required|string",
                "phone_number" => "required|string",
                "address" => "nullable|array"
            ];

            $validator = Validator::make($request->all(), $rules, $this->messages);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->setStatus(400, 'Bad Request');
                $this->responder->set('message', $validator->errors()->first());
                return $this->responder->response();
            }

            $user = Auth::user();
            $fields = $request->only([
                "email",
                "first_name",
                "last_name",
                "dob",
                "gender",
                "phone_number"
            ]);
            foreach ($fields as $key => $value) {
                $user->setAttribute($key, $value);
            }
            $user->save();

            $this->responder->setStatus(200, 'Ok');
            $this->responder->set('message', 'Profile updated');
            $this->responder->set('data', $user);
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function photo(Request $request)
    {
        $user = Auth::user();
        $profile = Files::where('foreign_table', 'users')
            ->where('foreign_id', $user->id)
            ->where('directory', 'users/profile')
            ->first();

        $this->responder->set('collection', 'User');
        $this->responder->set('message', 'Data retrieved');
        $this->responder->set('data', $profile);
        return $this->responder->response();
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function permissions(Request $request)
    {
        // $user = Auth::user();
        // $permissions = $user->getAllPermissions();
        // $permissions = Arr::pluck($permissions, 'name');

        $this->responder->set('collection', 'Permissions');
        $this->responder->set('message', 'Data retrieved');
        // $this->responder->set('data', $permissions);
        return $this->responder->response();
    }


    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function changePassword(Request $request)
    {

        try {

            $rules = [
                'password' => 'required|string|confirmed',
                'password_confirmation' => 'required_with:password|same:password|min:6'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->setStatus(400, 'Bad Request');
                $this->responder->set('message', $validator->errors()->first());
                return $this->responder->response();
            }

            $user = Auth::user();
            $user->password = bcrypt($request->password);
            $user->save();

            $this->responder->setStatus(200, 'Ok');
            $this->responder->set('message', 'Password changed!');
            $this->responder->set('data', $user);
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function forgotPassword(Request $request)
    {

        try {
            $rules = [
                'email' => 'required|email',
            ];

            $validator = Validator::make($request->all(), $rules, $this->messages);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->setStatus(400, 'Bad Request');
                $this->responder->set('message', $validator->errors()->first());
                return $this->responder->response();
            }

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            $status == Password::RESET_LINK_SENT ? true : false;
            if ($status) {
                $this->responder->setStatus(200, 'Ok');
                $this->responder->set('message', 'Reset link sent!');
                $this->responder->set('data', null);
            } else {
                $this->responder->setStatus(500, 'Server cannot send link to your email!');
                $this->responder->set('message', 'Server cannot send link to your email!');
                $this->responder->set('data', null);
            }
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }
}

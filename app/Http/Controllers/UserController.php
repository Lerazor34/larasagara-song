<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourcesRequest;
use App\Models\Roles;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $table_name = null;
    protected $model;
    protected $forms;
    protected $segment;
    protected $view;
    protected $segmentName;
    protected $reference;

    public function __construct(Request $request, User $model)
    {
        try {
            $this->segment = $request->segment(1);
            if (file_exists(app_path('Models/' . Str::studly($this->segment)) . '.php')) {
                $this->model = app("App\Models\\" . Str::studly($this->segment));
            } else {
                if ($model->checkTableExists($this->segment)) {
                    $this->model = $model;
                    $this->model->setTable($this->segment);
                }
            }

            if (!$this->model) abort(404);

            $this->view = 'backend.' . $this->segment;
            $this->table_name = $this->segment;
            $this->segmentName = Str::studly($this->segment);
            $this->forms = $this->model->getForms();
            $this->reference = $this->model->getReference();
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function list()
    {
        $this->view = view('backend.users.list', ['forms' => $this->forms]);
        return $this->view->with(
            [
                'forms' => $this->forms,
                'segmentName' => $this->segmentName
            ]
        );
    }

    public function create()
    {
        $roles = Roles::get();
        $this->view = view('backend.users.create');

        return $this->view->with(
            [
                'forms' => $this->forms,
                'roles' => $roles
            ]
        );
    }

    public function store(ResourcesRequest $request)
    {
        try {
            $fields = $request->only($this->model->getTableFields());
            $fields['password'] = bcrypt($request->password);
            foreach ($fields as $key => $value) {
                $this->model->setAttribute($key, $value);
            }
            $this->model->save();

            return back()->withInput()->with('success', Str::title(Str::singular($this->table_name)) . ' Created!');
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->withInput()->withErrors('Invalid Request!');
        }
    }

    public function edit(Request $request)
    {
        $roles = Roles::get();
        $reference = $this->reference;
        $breadcrumbs = $this->generateBreadcrumbs($request->segments(), $request->id);
        $model = $this->model;

        if (count($reference) > 0) {
            for ($i = 0; $i < count($reference); $i++) {
                $model = $model->with($reference[$i]);
            }
        }

        $model = $model->with('roles');
        $model = $model->findOrFail($request->id)->toArray();
        $userRoles = [];
        for ($i = 0; $i < count($model['roles']); $i++) {
            $userRoles[] = $model['roles'][$i]['roles_id']['id'];
        }

        $newForms = [];
        foreach ($this->forms as $key => $value) {
            if (isset($model[$value['name']])) {
                $value['value'] = $model[$value['name']];
            } else {
                $value['value'] = null;
            }
            $newForms[$key] = $value;
        }

        $this->view = view('backend.users.edit');
        return $this->view->with(
            [
                'forms' => $newForms,
                'breadcrumbs' => $breadcrumbs,
                'roles' => $roles,
                'userRoles' => $userRoles
            ]
        );
    }

    public function profile($userId = NULL)
    {
        if (!$userId) $userId = auth()->user()->id;

        $model = $this->model
            ->with(['roles', 'status', 'gender'])
            ->findOrFail($userId)
            ->toArray();

        if (!$model) abort(404);

        $this->view = view('backend.users.profile');
        return $this->view->with(
            [
                'model' => $model
            ]
        );
    }

    public function reset_password()
    {
        $userId = auth()->user()->id;

        $model = $this->model->with('roles')->findOrFail($userId)->toArray();

        if (!$model) abort(404);

        $this->view = view('backend.users.reset_password');
        return $this->view->with(
            [
                'model' => $model
            ]
        );
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $userId = auth()->user()->id;
            $model = $this->model::where('id', $userId);

            $model->setAttribute('password', bcrypt($request->password));
            $model->save();

            return redirect('user/reset_password')->with('success', Str::title(Str::singular('users')) . ' updated!');
        } catch (Exception $th) {
            return redirect('user/reset_password')->withError(Str::title(Str::singular('users')) . ' failed to update!');
        }
    }

    public function update(ResourcesRequest $request)
    {
        try {
            $model = $this->model->findOrFail($request->id);
            $fields = $request->only($this->model->getTableFields());
            $fields['password'] = bcrypt($request->password);

            foreach ($fields as $key => $value) {
                $model->setAttribute($key, $value);
            }

            $model->save();

            return back()->withInput()->with('success', Str::title(Str::singular($this->table_name)) . ' updated!');
        } catch (Exception $e) {
            return back()->withInput()->withError(Str::title(Str::singular($this->table_name)) . ' failed to update!');
        }
    }

    public function activation($userId, $status)
    {
        $statusName = [
            '1' => [
                'message' => 'Not Active',
                'status' => 2
            ],
            '2' => [
                'message' => 'Activated',
                'status' => 1
            ]
        ];

        try {
            $model = $this->model->find($userId);

            if (!$model) return redirect($this->table_name)->withError('User not found!');
            $model->setAttribute('status', $statusName[$status]['status']);
            $model->save();

            $message = "User with name $model->username is " . $statusName[$status]['message'];

            return redirect($this->table_name)->with('success', $message);
        } catch (Exception $e) {
            return redirect($this->table_name)->withError(Str::title(Str::singular($this->table_name)) . ' Error!');
        }
    }

    public function generateBreadcrumbs($segments = array(), $id)
    {
        $hirarcies = array();
        foreach ($segments as $item) {
            if ($item == $id) continue;
            $hirarcies[] = $item;
        }

        return $hirarcies;
    }
}

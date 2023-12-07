<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiUserController extends ApiGlobalController
{
    private $model;
    protected $reference;
    protected $forms;

    public function __construct(Request $request, User $model)
    {
        try {
            if (file_exists(app_path('Models/' . Str::studly('users')) . '.php')) {
                $this->model = app("App\Models\\" . Str::studly('users'));
            } else {
                if ($model->checkTableExists('users')) {
                    $this->model = $model;
                    $this->model->setTable('users');
                }
            }

            $this->reference = $this->model->getReference();
            $this->forms = $this->model->getForms();
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function activation($id, $status)
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
            $model = User::findOrFail($id);
            if (!$model) return response(['status' => 404, 'message' => 'Model not found']);

            $model->setAttribute('status', $statusName[$status]['status']);
            $model->save();

            $message = "User with name $model->username is " . $statusName[$status]['message'];

            return response(['status' => 200, 'message' => $message]);
        } catch (Exception $e) {
            return response(['status' => 500, 'message' => 'Internal Server Error!']);
        }
    }

    public function dataTable(Request $request)
    {
        $reference = $this->reference;
        $offset = $request->get('start') ? $request->get('start') : 0;
        $limit = $request->get('length') ? $request->get('length') : 10;
        $search = $request->get('search');
        $params = $request->get('params');
        $status = $request->get('status');

        $model = $this->model;
        $fields = $model->getFields();

        $forms = ['id'];
        foreach ($this->forms as $items) {
            if ($items['display']) $forms[] = $items['name'];
        }

        if ($status == 2) {
            $model = $model->onlyTrashed();
        }

        if (count($reference) > 0) {
            $model = $model->with($reference);
        }

        if (!empty($search)) {
            $model = $model->where(function ($model) use ($fields, $search) {
                foreach ($fields as $key => $item) {
                    if ($key == 0) {
                        $model->where($item, 'LIKE', '%' . $search . '%');
                    } else {
                        $model->orWhere($item, 'LIKE', '%' . $search . '%');
                    }
                }
            });
        }

        if (!empty($params)) {
            foreach ($params as $key => $item) {
                if (empty($item['value'])) continue;
                if ($item['name'] == 'role') {
                    $model->whereHas('roles', function ($query) use ($item) {
                        return $query->where('roles_id', $item['value']);
                    });
                } else {
                    $model->where($item['name'], $item['value']);
                }
            }
        }

        $total = $model->count();

        $order = 'desc';
        if ($request->get('order')[0]['column']) {
            $order = $request->get('order')[0]['dir'];
        }
        $model = $model->orderBy($forms[$request->get('order')[0]['column']], $order);

        $model = $model->offset($offset);
        $model = $model->limit($limit);
        $model = $model->get();

        $forms = [];
        foreach ($this->forms as $items) {
            $forms[$items['name']] = $items['type'];
        }

        $dataTable = [];
        foreach ($model->toArray() as $key => $items) {
            $roles = '';
            for ($i = 0; $i < count($items['roles']); $i++) {
                $roles .= "<span class='badge py-3 px-4 fs-7 badge-light-success me-1 mb-1'>" . $items['roles'][$i]['roles_id']['name'] . "</span>";
            }
            $items['roles'] = $roles;
            foreach ($items as $q => $value) {
                $data = $value;
                if (isset($forms[$q])) {
                    switch ($forms[$q]) {
                        case 'thumbnail':
                            $data = $this->thumbnail($value);
                            break;

                        default:
                            $data = $value;
                            break;
                    }
                }
                $dataTable[$key][$q] = $data;
            }
        }

        $draw = 1;
        if (!empty($request->get('draw'))) {
            $draw = $request->get('draw');
        }

        $data = [
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $dataTable
        ];

        return response($data);
    }
}

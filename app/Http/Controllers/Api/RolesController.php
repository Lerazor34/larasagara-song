<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RolesController extends Controller
{
    private $model;
    protected $reference;
    protected $forms;

    public function __construct(Request $request, Roles $model)
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

        $reference[] = 'getUsers';
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
            $users = '<div class="symbol-group symbol-hover flex-nowrap">';
            $count = count($items['get_users']);
            // $max = 1;
            // if ($count > $max) $count = $max;
            for ($i = 0; $i < $count; $i++) {
                if (!$items['get_users'][$i]['users_id']) continue;
                $img = json_decode($items['get_users'][$i]['users_id']['photo'], true);
                $image = asset('storage/avatar') . '/' . $img['filename'];
                if (!file_exists(public_path('storage/avatar/' . $img['filename']))) $image = asset('assets/media/avatars/blank.png');
                $users .= '
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $items['get_users'][$i]['users_id']['username'] . '">
                        <img alt="Pic" src="' . $image . '">
                </div>
            ';
            }
            // $users .= '
            //     <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
            //         <span class="symbol-label fs-8 fw-bold">+42</span>
            //     </a>
            // ';
            $users .= '</div>';
            $items['get_users'] = $users;
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

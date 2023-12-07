<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourcesRequest;
use App\Models\Priveleges;
use App\Models\RolePriveleges;
use App\Models\Roles;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    protected $table_name = null;
    protected $model;
    protected $forms;
    protected $segment;

    public function __construct(Request $request, Roles $model)
    {
        $this->segment = $request->segment(1);
        $this->table_name = $this->segment;
        $this->model = $model;
        $this->forms = $model->getForms();
    }
    public function list(Request $request)
    {
        return view('backend.roles.list', ['forms' => $this->forms]);
    }

    public function create()
    {

        $privelages = Priveleges::get()->toArray();

        $dataPrivelages = [];
        foreach ($privelages as $key => $items) {
            $dataPrivelages[$items['module']][$items['sub_module']][$key]['value'] = $items['namespace'];
            $dataPrivelages[$items['module']][$items['sub_module']][$key]['name'] = $items['module_name'];
        }

        $this->view = view('backend.roles.create');

        return $this->view->with(
            [
                'forms' => $this->forms,
                'privelages' => $dataPrivelages
            ]
        );
    }

    public function store(ResourcesRequest $request)
    {
        try {
            $role = Roles::create([
                'name' => $request->name
            ]);

            $this->createPriveleges($request->priveleges, $role->id);
            return redirect($this->table_name . '/create')->with('success', 'Role Created!');
        } catch (\Throwable $e) {
            dd($e);
            // return redirect($this->table_name . '/create')->withErrors('Failed Created Role!');
        }
    }

    public function edit(Request $request, $id)
    {

        $breadcrumbs = $this->generateBreadcrumbs($request->segments(), $id);

        $model = $this->model->with('permission')->findOrFail($id)->toArray();

        $permission = [];
        foreach ($model['permission'] as $key => $value) {
            $permission[] = $value['namespace'];
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

        $priveleges = Priveleges::get()->toArray();
        $dataPriveleges = [];
        foreach ($priveleges as $key => $items) {
            $dataPriveleges[$items['module']][$items['sub_module']][$key]['value'] = $items['namespace'];
            $dataPriveleges[$items['module']][$items['sub_module']][$key]['name'] = $items['module_name'];
        }

        $this->view = view('backend.roles.edit');
        return $this->view->with(
            [
                'forms' => $newForms,
                'breadcrumbs' => $breadcrumbs,
                'priveleges' => $dataPriveleges,
                'permission' => $permission
            ]
        );
    }

    public function update(ResourcesRequest $request, $id = null)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        try {
            $model = $this->model::find($id);
            $fields = $request->only($this->model->getTableFields());
            foreach ($fields as $key => $value) {
                $model->setAttribute($key, $value);
            }

            $model->save();

            $this->createPriveleges($request->priveleges, $id);
            return redirect($this->table_name)->with('success', Str::title(Str::singular($this->table_name)) . ' updated!');
        } catch (Exception $e) {
            dd($e);
            // return redirect($this->table_name . '/' . $id . '/edit')->withErrors(Str::title(Str::singular($this->table_name)) . ' failed to update!');
        }
    }

    public function trash($id)
    {
        try {
            $model = $this->model::find($id);
            $model->setAttribute('deleted_at', date('Y-m-d H:i:s'));
            $model->save();

            return redirect($this->table_name)->with('success', Str::title(Str::singular($this->table_name)) . ' deleted!');
        } catch (Exception $th) {
            return redirect($this->table_name)->withErrors(Str::title(Str::singular($this->table_name)) . ' failed to delete!');
        }
    }

    public function delete(Request $request, $id = null)
    {
        if (!$this->model) abort(404);
        try {
            $model = $this->model::findOrFail($id);
            $model->delete();
            return redirect($this->table_name)->with('success', Str::title(Str::singular($this->table_name)) . ' deleted!');
        } catch (Exception $e) {
            return redirect($this->table_name)->with('error', $e->getMessage());
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

    public function createPriveleges($data, $roleId)
    {
        $checkDataExist = RolePriveleges::where(['role' => $roleId]);

        if ($checkDataExist->count() > 0) {
            $checkDataExist->delete();
        }

        for ($i = 0; $i < count($data); $i++) {
            $newPermission = [
                'role' => $roleId,
                'namespace' => $data[$i]
            ];

            RolePriveleges::create($newPermission);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourcesRequest;
use Illuminate\Support\Str;
use App\Models\Resources;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AppController extends Controller
{
    protected $view;
    protected $model = null;
    protected $segment = null;
    protected $table_name = null;
    protected $segmentName;
    protected $forms;
    protected $reference;

    public function __construct(Request $request, Resources $model)
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
        $model = $this->model;
        if (!$model) abort(404);
        try {
            $this->view = $this->checkView($this->view, 'list');
            return $this->view->with(
                [
                    'forms' => $this->forms,
                    'segmentName' => $this->segmentName
                ]
            );
        } catch (Exception $e) {
            abort(404);
        }
    }

    public function create()
    {
        $this->view = $this->checkView($this->view, 'create');

        return $this->view->with(
            [
                'forms' => $this->forms
            ]
        );
    }

    public function store(ResourcesRequest $request)
    {
        try {
            $fields = $request->only($this->model->getTableFields());
            foreach ($fields as $key => $value) {
                $this->model->setAttribute($key, $value);
            }
            $this->model->save();

            return back()->withInput()->with('success', Str::title(Str::singular($this->table_name)) . ' Created!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors('Invalid Request!');
        }
    }

    public function detail(Request $request)
    {
        $reference = $this->reference;
        $breadcrumbs = $this->generateBreadcrumbs($request->segments(), $request->id);
        $model = $this->model;

        if (count($reference) > 0) {
            for ($i = 0; $i < count($reference); $i++) {
                $model = $model->with($reference[$i]);
            }
        }
        $model = $model->findOrFail($request->id)->toArray();

        $newForms = [];
        foreach ($this->forms as $key => $value) {
            if (isset($model[$value['name']])) {
                $value['value'] = $model[$value['name']];
            } else {
                $value['value'] = null;
            }
            $newForms[$key] = $value;
        }

        $this->view = $this->checkView($this->view, 'detail');
        return $this->view->with(
            [
                'forms' => $newForms,
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    public function edit(Request $request)
    {

        $reference = $this->reference;
        $breadcrumbs = $this->generateBreadcrumbs($request->segments(), $request->id);
        $model = $this->model;

        if (count($reference) > 0) {
            for ($i = 0; $i < count($reference); $i++) {
                $model = $model->with($reference[$i]);
            }
        }
        $model = $model->findOrFail($request->id)->toArray();

        $newForms = [];
        foreach ($this->forms as $key => $value) {
            if (isset($model[$value['name']])) {
                $value['value'] = $model[$value['name']];
            } else {
                $value['value'] = null;
            }
            $newForms[$key] = $value;
        }

        $this->view = $this->checkView($this->view, 'edit');
        return $this->view->with(
            [
                'forms' => $newForms,
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    public function update(ResourcesRequest $request)
    {
        try {
            $model = $this->model->findOrFail($request->id);
            $fields = $request->only($this->model->getTableFields());

            foreach ($fields as $key => $value) {
                $model->setAttribute($key, $value);
            }

            $model->save();

            return back()->withInput()->with('success', Str::title(Str::singular($this->table_name)) . ' updated!');
        } catch (Exception $e) {
            return back()->withInput()->withError(Str::title(Str::singular($this->table_name)) . ' failed to update!');
        }
    }

    public function trash(Request $request)
    {
        try {
            $model = $this->model->findOrFail($request->id);
            $model->delete();

            return redirect($this->table_name)->with('success', Str::title(Str::singular($this->table_name)) . ' deleted!');
        } catch (Exception $th) {
            return redirect($this->table_name)->withError(Str::title(Str::singular($this->table_name)) . ' failed to delete!');
        }
    }

    public function trashed()
    {
        $model = $this->model;

        if (!$model) abort(404);
        try {
            $this->view = $this->checkView($this->view, 'trashed');
            return $this->view->with(
                [
                    'forms' => $this->forms,
                    'segmentName' => $this->segmentName
                ]
            );
        } catch (Exception $e) {
            abort(404);
        }
    }

    public function delete(Request $request)
    {
        if (!$this->model) abort(404);
        try {
            $model = $this->model->onlyTrashed()->findOrFail($request->id);
            $model->forceDelete();
            return redirect($this->table_name . '/trashed')->with('success', Str::title(Str::singular($this->table_name)) . ' deleted!');
        } catch (Exception $e) {
            return redirect($this->table_name . '/trashed')->with('error', $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        if (!$this->model) abort(404);
        try {
            $model = $this->model->onlyTrashed()->findOrFail($request->id);
            $model->restore();
            return redirect($this->table_name)->with('success', Str::title(Str::singular($this->table_name)) . ' Restored!');
        } catch (Exception $e) {
            return redirect($this->table_name)->with('error', $e->getMessage());
        }
    }

    public function checkView($dir, $fileName)
    {
        $directory = $dir . '.' . $fileName;
        $dirname = str_replace('.', '/', $directory);
        $basePath = base_path('resources/views/') . $dirname . '.blade.php';
        if (file_exists($basePath)) return view($directory);
        return view('backend.shared.' . $fileName);
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

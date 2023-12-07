<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Files;
use App\Models\Resources;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ApiGlobalController extends Controller
{
    private $model;
    protected $reference;
    protected $forms;
    protected $segment;
    protected $table_name;

    public function __construct(Request $request, Resources $model)
    {
        try {
            $this->segment = $request->segment(2);
            if (file_exists(app_path('Models/' . Str::studly($this->segment)) . '.php')) {
                $this->model = app("App\Models\\" . Str::studly($this->segment));
            } else {
                if ($model->checkTableExists($this->segment)) {
                    $this->model = $model;
                    $this->model->setTable($this->segment);
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
        $orderBy = $request->get('order');
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
                if (!empty($item['value'])) $model->where($item['name'], $item['value']);
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
            foreach ($items as $q => $value) {
                $data = $value;
                if (isset($forms[$q])) {
                    switch ($forms[$q]) {
                        case 'thumbnail':
                            $data = $this->thumbnail($value);
                            break;
                            // case 'select2':
                            //     dd($value, $forms[$q]);
                            //     $data = !empty($value) ? $value[$forms[$q]['option']['display']] : null;
                            //     break;
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

    public function thumbnail($value)
    {
        $file = asset('assets/media/avatars/150-26.jpg');
        if (!empty($value)) {
            $image = json_decode($value, true);
            $file = url('storage/avatar/', $image['filename']);
        }

        $data = ['file' => $file];
        return view('_forms.thumbnail.plain', compact('data'))->render();
    }

    public function trash(Request $request)
    {
        try {
            $selectedId = explode(',', $request->id);
            for ($i = 0; $i < count($selectedId); $i++) {
                $model = $this->model->findOrFail($selectedId[$i]);
                $model->delete();
            }

            $data = [
                'status' => 200
            ];
            return response($data);
        } catch (Exception $th) {
            return redirect($this->table_name)->withError(Str::title(Str::singular($this->table_name)) . ' failed to delete!');
        }
    }

    public function delete(Request $request)
    {
        try {
            $selectedId = explode(',', $request->id);
            $listFileCode = [];
            for ($i = 0; $i < count($selectedId); $i++) {
                $model = $this->model->onlyTrashed()->findOrFail($selectedId[$i]);

                if ($this->model->getFilesList()) {
                    $fileList = $this->model->getFilesList();
                    for ($x = 0; $x < count($fileList); $x++) {
                        $name = $fileList[$i];
                        $listFileCode[] = $model->$name;
                    }
                }

                $model->forceDelete();
            }

            if (count($listFileCode) > 0) $this->deleteFiles($listFileCode);

            $data = [
                'status' => 200,
                'message' => 'Rows Deleted'
            ];
            return response($data);
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
            return response($data);
        }
    }

    public function deleteFiles($listCode)
    {
        Files::whereIn('code', $listCode)->each(function ($data, $items) {
            $originalFile = public_path('storage/image/origin/' . $data->original_name);
            $compressedFile = public_path('storage/image/compress/' . $data->compressed_name);

            if (File::exists($originalFile)) File::delete($originalFile);
            if (File::exists($compressedFile)) File::delete($compressedFile);

            $data->delete();
        });
    }

    public function restore(Request $request)
    {
        try {
            $model = $this->model->onlyTrashed()->findOrFail($request->id);
            $model->restore();
            $data = [
                'status' => 200,
                'message' => 'Rows Restored'
            ];
            return response($data);
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
            return response($data);
        }
    }
}

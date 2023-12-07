<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ComponentController extends Controller
{
    protected $model;
    protected $key;
    protected $display;

    public function __construct(Request $request)
    {
        $this->model = $request->get('model');
        $this->key = $request->get('key');
        $this->display = $request->get('display');
    }

    public function file_upload(Request $request)
    {

        $destinationPath = public_path('storage/images');

        if (!empty($request->get('bucket'))) $destinationPath = public_path('storage/' . $request->get('bucket'));

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0766, true);
        }

        $files = $request->file();

        $uploaded = [];
        foreach ($files as $key => $value) {
            $extension = $value->getClientOriginalExtension();
            $randomfile = time() . '.' . $extension;
            $file = Image::make($value->getRealPath());
            // $file->save($destinationPath . '/' . $randomfile);
            $file->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $randomfile);

            $data = [
                'name' => $key,
                'filename' => $randomfile,
                'bucket' => $destinationPath,
                'mime_type' => $value->getClientMimeType()
            ];
            $uploaded[] = $data;
        }

        return response($uploaded);
    }

    public function deleteFile(Request $request)
    {
        $bucket = 'storage/';
        if (!empty($request->get('bucket'))) $bucket .= $request->get('bucket') . '/';
        $bucket .= $request->get('filename');
        $dirFile = $bucket;

        $file = public_path($dirFile);
        if (File::exists($file)) {
            File::delete($file);

            $response = [
                'message' => 'File deleted successfully'
            ];
            return response($response);
        }
        return response(['message' => 'File not exist']);
    }

    public function select2(Request $request)
    {
        $table = $this->model;
        $key = $this->key;
        $value = $this->display;

        $whereLike = "";
        if (!empty($request->get('search'))) {
            $whereLike = "AND tb." . $value . " LIKE '%" . $request->get('search') . "%'";
        }

        $query = "SELECT 
                    tb." . $key . " AS id,
                    tb." . $value . " AS text
                FROM $table AS tb
                WHERE tb.deleted_at IS NULL
                $whereLike
                LIMIT 15";

        $result = DB::select($query);

        return response(['results' => $result]);
    }

    public function sysparam(Request $request)
    {
        $group = $request->get('group');
        $value = $request->get('display');

        $whereLike = "";
        if (!empty($request->get('search'))) {
            $whereLike = "AND tb." . $value . " LIKE '%" . $request->get('search') . "%'";
        }

        $query = "SELECT 
                    tb.key AS id,
                    tb." . $value . " AS text
                FROM sysparams AS tb
                WHERE tb.deleted_at IS NULL
                AND tb.groups = '$group'
                $whereLike
                LIMIT 15";

        $result = DB::select($query);

        return response(['results' => $result]);
    }
}

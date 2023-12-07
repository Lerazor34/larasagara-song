<?php

namespace App\Observers;

use App\Models\Resources;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class FileUploadObserver
{
    /**
     * Handle the Resources "created" event.
     *
     * @param  \App\Models\Resources  $resources
     * @return void
     */
    protected $isNew;

    public function __construct(Resources $resources)
    {
        $this->isNew = request()->id ? false : true;
    }

    public function saving(Resources $resources)
    {
        $listFile = $resources->getFilesList();

        if (count($listFile) > 0) {
            for ($i = 0; $i < count($listFile); $i++) {
                $name = $listFile[$i];
                $removeFile = 'remove_' . $name;
                if (isset(request()->$removeFile)) {
                    $this->removeFile($resources->$name);
                    $resources->$name = NULL;
                }
            }
        }

        if (request()->file()) {
            foreach (request()->file() as $key => $file) {
                $code = Str::random(15);
                $resources->$key = $this->writeFile($file, $code);
            }
        }
    }

    public function saved(Resources $resources)
    {
        // ini untuk check apakah file nya berubah
        if (!$this->isNew && request()->file()) {
            foreach (request()->file() as $key => $file) {
                if ($resources->isDirty($key)) {
                    $code = $resources->getOriginal($key);
                    $this->removeFile($code);
                }
            }
        }
    }

    public function writeFile($file, $code)
    {
        $imageType = ['jpg', 'jpeg', 'png', 'webp'];
        $files = ['pdf', 'doc', 'xls', 'xlsx', 'zip', 'mp4', 'mov'];

        $ext = $file->getClientOriginalExtension();

        if (in_array($ext, $imageType)) $this->saveAsImage($file, $code);
        if (in_array($ext, $files)) $this->saveAsFile($file, $code);

        return $code;
    }

    public function saveAsImage($file, $code)
    {
        $path = 'origin/';
        $path .= $file->getClientMimeType();

        $file->store($path, 'public');

        $fullPath = $path . '/' . $file->hashName();
        $compressImage = $this->writeFileCompress($file);
        $dataFile = [
            'code' => $code,
            'name' => $file->getClientOriginalName(),
            'original_name' => $file->hashName(),
            'compressed_name' => $compressImage['name'],
            'description' => '',
            'original_uri' => $fullPath,
            'compressed_uri' => $compressImage['url'],
            'mimetype' => $file->getClientMimeType()
        ];

        DB::table('files')->insert($dataFile);

        return $code;
    }

    public function saveAsFile($file, $code)
    {
        $path = 'storage/files/';
        $path .= $file->getClientMimeType();

        $filename = $file->hashName();
        $directory = public_path($path);
        $file->move($directory, $filename);

        $fullPath = $path . '/' . $filename;
        $dataFile = [
            'code' => $code,
            'name' => $file->getClientOriginalName(),
            'original_name' => $filename,
            'compressed_name' => '',
            'description' => '',
            'original_uri' => $fullPath,
            'compressed_uri' => '',
            'mimetype' => $file->getClientMimeType()
        ];

        DB::table('files')->insert($dataFile);

        return $code;
    }

    public function writeFileCompress($file)
    {
        $mimeType = $file->getClientMimeType();
        $path = 'storage/compress/' . $mimeType;

        $fulPath = 'compress/' . $mimeType;
        $directory = public_path($path);

        if (!file_exists($directory)) {
            mkdir($directory, 755, true);
        }

        $name = $file->hashName();
        $path = $directory . '/' . $name;
        $file = Image::make($file->getRealPath());
        $file->resize(900, 720, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path);

        return [
            'name' => $name,
            'url' => $fulPath
        ];
    }

    public function removeFile($code)
    {
        $file = DB::table('files')->where('code', $code);
        $data = $file->first();
        $originalFile = public_path('storage/origin/' . $data->mimetype . '/' . $data->original_name);
        $compressedFile = public_path('storage/compress/' . $data->mimetype . '/' . $data->compressed_name);
        $pdfFile = public_path('storage/file/pdf/' . $data->original_name);

        if (File::exists($originalFile)) File::delete($originalFile);
        if (File::exists($compressedFile)) File::delete($compressedFile);
        if (File::exists($pdfFile)) File::delete($pdfFile);

        $file->delete();
    }

    public function updateFileDescription($desc, $code)
    {
        $file = DB::table('files')->where('code', $code);
        $data = $file->first();

        if ($data->description == $desc) return false;

        $data = ['description' => $desc];
        $file->update($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Vocabularies;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VocabulariesController extends AppController
{
    public function generateLanguage(Request $request)
    {
        try {
            $code = $request->get('code');
            $collection = Vocabularies::where('countries_id', $code)->get()->toArray();
            if (count($collection) <= 0) return back()->withErrors(__('Collection is empty'));

            $data = [];
            foreach ($collection as $items) {
                $data[$items['key']] = $items['translate'];
            }

            $file = base_path() . "/lang/" . $code . ".json";
            if (file_exists($file)) {
                unlink($file);
            }

            $data = json_encode($data);
            File::put($file, $data);

            return back()->with('success', __('Language generated'));
        } catch (Exception $e) {
            dd($e);
            return back()->withErrors(__('Failed to generate language'));
        }
    }
}

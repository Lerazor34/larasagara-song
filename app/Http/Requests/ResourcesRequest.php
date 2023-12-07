<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResourcesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $model;
    protected $segment;
    protected $isNew;

    public function __construct(Request $request)
    {
        $this->segment = Str::studly($request->segment(1));
        $this->model = app("App\Models\\" . $this->segment);
        $this->isNew = $request->id;
    }


    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = $this->model->getRules();
        $newRules = [];
        foreach ($rules as $key => $item) {
            for ($i = 0; $i < count($item); $i++) {
                $newRules[$key][$i] = $item[$i];
                if ($item[$i] == 'unique' && !$this->isNew) {
                    $newRules[$key][$i] = $item[$i] . ":" . $this->model->getTable() . "," . $key . ",NULL,id,deleted_at,NULL";
                } elseif ($item[$i] == 'unique' && $this->isNew) {
                    $newRules[$key][$i] = $item[$i] . ":" . $this->model->getTable() . "," . $key . "," . $this->isNew . ",id,deleted_at,NULL";
                }
            }
        }

        if (!$this->isNew && $this->model->createRules()) {
            $newRules = array_merge($newRules, $this->model->createRules());
        }

        if ($this->isNew && $this->model->updateRules()) {
            $newRules = array_merge($newRules, $this->model->updateRules());
        }

        return $newRules;
    }
}

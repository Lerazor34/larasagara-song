<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vocabularies extends Resources
{
    use HasFactory;

    protected $guard_name = 'web';
    protected $table = 'vocabularies';

    protected $rules = [
        'countries_id' => ['required'],
        'key' => ['required', 'unique', 'string', 'max:20'],
        'translate' => ['required', 'unique', 'string', 'max:100']
    ];

    protected $forms = [
        [
            'name' => 'countries_id',
            'required' => true,
            'column' => 3,
            'label' => 'Countries',
            'type' => 'select2',
            'options' => [
                'model' => 'countries',
                'key' => 'code',
                'display' => 'name'
            ],
            'display' => true
        ],
        [
            'name' => 'key',
            'required' => true,
            'column' => 4,
            'label' => 'Key',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'translate',
            'required' => true,
            'column' => 4,
            'label' => 'Translate',
            'type' => 'text',
            'display' => true
        ]
    ];

    protected $fillable = [
        'id',
        'countries_id',
        'key',
        'translate'
    ];

    protected $reference = [
        'countries_id'
    ];

    public function getFields()
    {
        return $this->fillable;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function countries_id()
    {
        return $this->hasOne(Countries::class, 'code', 'countries_id');
    }
}

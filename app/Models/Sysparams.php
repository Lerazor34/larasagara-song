<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sysparams extends Resources
{
    use HasFactory;

    protected $guard_name = 'web';
    protected $table = 'sysparams';

    protected $rules = [
        'groups' => ['required', 'string', 'max:50'],
        'key' => ['required', 'string', 'max:20'],
        'value' => ['required', 'string', 'max:255'],
        'ordering' => ['required', 'integer', 'max:11']
    ];

    protected $forms = [
        [
            'name' => 'groups',
            'required' => true,
            'column' => 3,
            'label' => 'Groups',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'key',
            'required' => true,
            'column' => 2,
            'label' => 'Key',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'value',
            'required' => true,
            'column' => 4,
            'label' => 'Value',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'ordering',
            'required' => true,
            'column' => 3,
            'label' => 'Ordering',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'long_value',
            'required' => false,
            'column' => 3,
            'label' => 'Long Value',
            'type' => 'text',
            'display' => true
        ]
    ];

    protected $fillable = [
        'id',
        'groups',
        'key',
        'value',
        'ordering',
        'long_value'
    ];

    public function getFields()
    {
        return $this->fillable;
    }
    public function getRules()
    {
        return $this->rules;
    }
}

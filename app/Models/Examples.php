<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examples extends Resources
{
    use HasFactory;

    protected $guard_name = 'web';
    protected $table = 'examples';

    protected $rules = [
        'name' => [
            'create' => 'required', 'string', 'max:50',
            'update' => 'required', 'string', 'max:50',
            'delete' => 'nullable',
        ],
        'age' => [
            'create' => 'required', 'number',
            'update' => 'required', 'number',
            'delete' => 'nullable',
        ],
        'birth_of_date' => [
            'create' => 'required', 'date',
            'update' => 'required', 'date',
            'delete' => 'nullable',
        ],
        'birth_of_date_string' => [
            'create' => 'required', 'string',
            'update' => 'required', 'string',
            'delete' => 'nullable',
        ],
    ];

    protected $fillable = [
        'name',
        'age',
        'birth_of_date',
        'birth_of_date_string',
    ];

    protected $reference = [];
}

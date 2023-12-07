<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleUsers extends Resources
{
    use HasFactory;

    protected $guard_name = 'web';
    protected $table = 'role_users';

    protected $rules = [
        'users_id' => ['required'],
        'roles_id' => ['required']
    ];

    protected $forms = [
        [
            'name' => 'users_id',
            'required' => true,
            'column' => 4,
            'label' => 'User',
            'type' => 'select2',
            'options' => [
                'model' => 'users',
                'key' => 'id',
                'display' => 'username'
            ],
            'display' => true
        ],
        [
            'name' => 'roles_id',
            'required' => true,
            'column' => 5,
            'label' => 'Role',
            'type' => 'select2',
            'options' => [
                'model' => 'roles',
                'key' => 'id',
                'display' => 'name'
            ],
            'display' => true
        ]
    ];

    protected $fillable = [
        'id',
        'users_id',
        'roles_id'
    ];

    protected $reference = [
        'users_id',
        'roles_id'
    ];

    public function getReference()
    {
        return $this->reference;
    }

    public function getFields()
    {
        return $this->fillable;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function users_id()
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

    public function roles_id()
    {
        return $this->hasOne(Roles::class, 'id', 'roles_id');
    }

    public function rolePrevileges()
    {
        return $this->hasMany(RolePriveleges::class, 'role', 'roles_id');
    }
}

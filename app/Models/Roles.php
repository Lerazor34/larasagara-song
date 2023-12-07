<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Roles extends Resources
{
    use HasFactory;

    protected $table = 'roles';

    protected $rules = [
        'name' => ['required', 'string', 'max:255']
    ];

    protected $fillable = [
        'name',
    ];

    protected $searchable = [
        'name'
    ];

    protected $hidden = [];

    protected $forms = [
        [
            'name'      => 'name',
            'required'  => true,
            'column'    => 4,
            'label'     => 'Name',
            'type'      => 'text',
            'display'   => true
        ],
    ];

    public function getRules()
    {
        return $this->rules;
    }

    public function getFields()
    {
        return $this->fillable;
    }

    public function getForms()
    {
        return $this->forms;
    }

    public function checkTableExists($table_name)
    {
        return Schema::hasTable($table_name);
    }

    public function getTableFields()
    {
        return Schema::getColumnListing($this->getTable());
    }

    public function getUser()
    {
        return $this->hasMany(User::class, 'role', 'id');
    }

    public function getPrivilege()
    {
        return $this->hasMany(Priveleges::class, 'id');
    }

    public function permission()
    {
        return $this->hasMany(RolePriveleges::class, 'role', 'id');
    }

    public function getUsers()
    {
        return $this->hasMany(RoleUsers::class)->with('users_id');
    }
}

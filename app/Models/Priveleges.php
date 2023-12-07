<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Priveleges extends Resources
{
    use HasFactory;

    protected $table = 'priveleges';

    protected $fillable = [
        'module',
        'sub_module',
        'module_name',
        'namespace',
        'ordering'
    ];

    protected $forms = [
        [
            'name'      => 'module',
            'required'  => true,
            'column'    => 4,
            'label'     => 'Module',
            'type'      => 'text',
            'display'   => true
        ],
        [
            'name'      => 'sub_module',
            'required'  => true,
            'column'    => 4,
            'label'     => 'Sub Modul',
            'type'      => 'text',
            'display'   => true
        ],
        [
            'name'      => 'module_name',
            'required'  => true,
            'column'    => 4,
            'label'     => 'Module Name',
            'type'      => 'text',
            'display'   => true
        ],
        [
            'name'      => 'namespace',
            'required'  => true,
            'column'    => 4,
            'label'     => 'Namespace',
            'type'      => 'text',
            'display'   => true
        ],
        [
            'name'      => 'ordering',
            'required'  => true,
            'column'    => 4,
            'label'     => 'Order',
            'type'      => 'text',
            'display'   => true
        ],
    ];

    protected $hidden = [];

    protected $rules = [
        'module'        => ['required', 'string'],
        'sub_module'    => ['required', 'string'],
        'module_name'   => ['required', 'string'],
        'namespace'     => ['required', 'string'],
        'ordering'      => ['required', 'string']
    ];

    protected $searchable = [
        'module',
        'sub_module',
        'module_name'
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
        return $this->hasMany(User::class, 'user_id', 'id');
    }

    public function getRole()
    {
        return $this->hasMany(Roles::class, 'role_id', 'id');
    }

    public function getPrivilege()
    {
        return $this->belongsTo(Priveleges::class, 'id');
    }

    public function permission()
    {
        return $this->hasMany(RolePriveleges::class, 'id');
    }
}

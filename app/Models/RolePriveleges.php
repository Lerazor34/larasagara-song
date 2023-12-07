<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePriveleges extends Model
{
    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

    use HasFactory;

    protected $fillable = [
        'id',
        'role',
        'namespace'
    ];

    public function getRole()
    {
        return $this->hasMany(Roles::class, 'id');
    }

    public function getPrivilege()
    {
        return $this->hasMany(Privileges::class, 'id');
    }
}

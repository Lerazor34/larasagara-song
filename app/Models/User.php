<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

    use HasFactory;
    use HasApiTokens;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $rules = [
        'first_name' => ['required', 'string', 'max:255'],
        'password' => ['required', 'confirmed'],
        'username' => ['required', 'unique'],
        'email' => ['required', 'unique']
    ];

    protected $fillable = [
        'id',
        'photo',
        'first_name',
        'last_name',
        'username',
        'email',
        'gender',
        'address',
        'phone_number',
        'password'
    ];

    protected $forms = [
        [
            'name' => 'photo',
            'required' => true,
            'column' => 3,
            'label' => 'Photo',
            'type' => 'thumbnail',
            'display' => true
        ],
        [
            'name' => 'first_name',
            'required' => true,
            'column' => 3,
            'label' => 'First Name',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'last_name',
            'required' => true,
            'column' => 3,
            'label' => 'Last Name',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'username',
            'required' => true,
            'column' => 3,
            'label' => 'Username',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'email',
            'required' => true,
            'column' => 3,
            'label' => 'Email',
            'type' => 'email',
            'display' => true
        ],
        [
            'name' => 'gender',
            'required' => true,
            'column' => 2,
            'label' => 'Gender',
            'type' => 'sysparam',
            'options' => [
                'key' => 'key',
                'display' => 'value',
                'group' => 'Gender'
            ],
            'display' => true
        ],
        [
            'name' => 'address',
            'required' => true,
            'column' => 5,
            'label' => 'Address',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'phone_number',
            'required' => true,
            'column' => 3,
            'label' => 'Phone Number',
            'type' => 'text',
            'display' => true
        ],
        [
            'name' => 'password',
            'required' => true,
            'column' => 6, // max 9
            'label' => 'Password',
            'type' => 'password',
            'display' => false
        ],
        [
            'name' => 'status',
            'required' => true,
            'column' => 2,
            'label' => 'Status',
            'type' => 'sysparam',
            'options' => [
                'key' => 'key',
                'display' => 'value',
                'group' => 'Activation'
            ],
            'hidden' => true,
            'display' => true
        ],
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $reference = [
        'status',
        'gender',
        'roles'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRules()
    {
        return $this->rules;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function getFields()
    {
        return $this->fillable;
    }

    public function getForms()
    {
        return $this->forms;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function checkTableExists($table_name)
    {
        return Schema::hasTable($table_name);
    }

    public function getTableFields()
    {
        return Schema::getColumnListing($this->getTable());
    }

    public function getFilesList()
    {
        return [];
    }

    public function status()
    {
        return $this->belongsTo(Sysparams::class, 'status', 'key')
            ->withTrashed()
            ->where('groups', 'Activation');
    }

    public function updateRules()
    {
        return false;
    }

    public function createRules()
    {
        return false;
    }

    public function gender()
    {
        return $this->belongsTo(Sysparams::class, 'gender', 'key')
            ->withTrashed()
            ->where('groups', 'Gender');
    }

    public function roles()
    {
        return $this->hasMany(RoleUsers::class, 'users_id', 'id')->with('roles_id');
    }
}

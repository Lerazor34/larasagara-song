<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\Models\Songs;

class Artists extends Resources
{
    use HasFactory;

    protected $table = 'artists';

    protected $rules = [
        'Name' => ['required', 'string', 'max:100']
    ];

    protected $fillable = [
        
        'Name'
        
    ];

    protected $searchable = [
        
        'Name',
        'songs'
        
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_time',
        'updated_time'
    ];

    protected $forms = [
        
        [
            'Name'    => 'Name',
            'required'  => true,
            'column'    => 4,
            'label'     => 'Name',
            'type'      => 'text',
            'display'   => true
        ]
        
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
    public function songs(){
        return $this->belongsToMany(Songs::class, 'songs_artists');
    }
}

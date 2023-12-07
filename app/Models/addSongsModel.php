<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;
use App\Models\Artists;
use App\Models\Genres;


class addSongsModel extends Resources
{
    use HasFactory;

    protected $table = 'songs';

    protected $rules = [
        'Name' => ['required', 'string', 'max:50'],
        'genres_id' => ['required', 'integer', 'max:100'],
        'artists_id' => ['reqruired', 'integer', 'max:100'],
        'publishedDate' => ['required', 'date', 'max:50']
    ];

    protected $fillable = [
        'Name',
        'genres_id',
        'artists_id',
        'publishedDate'
    ];

    protected $searchable = [
        'Name'
        // 'artists',
        // 'genres'
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_time',
        'updated_time'
    ];

    protected $forms = [
        [
            'Name'      => 'Name',
            'required'  => true,
            'column'    => 4,
            'label'     => 'name',
            'type'      => 'text',
            'display'   => true
        ],
        // [
        //     'artists'    => 'artists',
        //     'required'  => true,
        //     'column'    => 4,
        //     'label'     => 'artists',
        //     'type'      => 'text',
        //     'display'   => true
        // ],
        [
            'genres_id'      => 'genres_id',
            'required'  => true,
            'column'    => 4,
            'label'     => 'genres',
            'type'      => 'number',
            'display'   => true
        ],
        [
            'artists_id'      => 'artists_id',
            'required'  => true,
            'column'    => 4,
            'label'     => 'artists',
            'type'      => 'number',
            'display'   => true
        ],
        [
            'publishedDate'     => 'publishedDate',
            'required'  => true,
            'column'    => 4,
            'label'     => 'publishedDate',
            'type'      => 'date',
            'display'   => true
        ],
        // [
        //     'artists_id'      => 'artists_id',
        //     'required'  => true,
        //     'column'    => 4,
        //     'label'     => 'artists',
        //     'type'      => 'number',
        //     'display'   => true
        // ]
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

    public function genres()
    {
        return $this->belongsTo(Genres::class);
    }
    public function artists()
    {
        return $this->belongsToMany(Artists::class,'songs_artists');
    }
}
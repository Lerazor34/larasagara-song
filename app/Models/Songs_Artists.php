<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Songs_Artists extends Resources
{
    use HasFactory;
    protected $table = "songs_artists";
    protected $rules = [
        'songs_id' => ['required', 'integer', 'max:100'],
        'artists_id' => ['required','integer', 'max:100']
    ];

    public function songs(){
        return $this->hasMany(Songs::class);
    }
    public function artists(){
        return $this->hasMany(Artists::class);
    }
}

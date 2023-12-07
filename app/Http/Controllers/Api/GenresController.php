<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\ApiResourcesController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genres;
use App\Models\Songs;

class GenresController extends ApiResourcesController
{
    public function genreses(){
        $genres = Genres::all();
        return response()->json($genres);
    }

    public function genresSongs(){
        $songs = Songs::with('genres')->get();
        return response()->json($songs);
    }

    public function genresSongsSearch($songsId){
        $songs = songs::where('id',$songsId)->with('genres')->get();
        return response()->json($songs);
    }
}

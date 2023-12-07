<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\ApiResourcesController;
use App\Http\Controllers\Controller;
use App\Models\Artists;
use App\Models\Songs;
use Illuminate\Http\Request;

class ArtistsController extends ApiResourcesController
{
    public function showArtists(){
        $genres = Artists::all();
        return response()->json($genres);
    }

    public function artistsSongs(){
        $songs = Artists::with('songs')->get();
        return response()->json($songs);
    }

    public function artistsSongsSearch($artistsId){
        $songs = Songs::where('id',$artistsId)->with('artists')->get();
        return response()->json($songs);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Songs;
use App\Models\Genres;
use App\Models\Artists;
use App\Models\Songs_Artists;

use function Laravel\Prompts\select;

class SongsController extends ApiResourcesController
{   

    public function songsArtists(){
        $artists = Songs::with('artists')->get();
        return response()->json($artists);
    }

    public function songsGenres(){
        $genres = Genres::with('songs')->get();
        return response()->json($genres);
    }

    public function songsGenresSearch($genresId){
        $genres = genres::where('id',$genresId)->with('songs')->get();
        return response()->json($genres);
    }

    public function songsArtistsSearch($songsId){
        $artists = Artists::where('id',$songsId)->with('songs')->get();
        return response()->json($artists);
    }

    
    public function storeArtists(Request $request){
        // Get all request data
        $attr = $request->all();
        // dd($attr);  
        // Create a new song record and retrieve its ID
        $datasongs = Songs::create([
            'Name' => $request -> Name,
            'genres_id' => $request -> genres_id,
            'publishedDate' => $request -> publishedDate
        ]);
        
        // Check if artists are available in the request
        if ($request->has('artists_id')) {
            // Check if there are artists data in the request
            if (count($request->artists) > 0) {
                $artists = $request->artists;
                // Loop through the artists and create records in the Songs_Artists table
                foreach ($artists as $artistId) {
                    Songs_Artists::create([
                        'songs_id' => $datasongs->id,
                        'artists_id' => $artistId
                    ]);
                    // $datasongs->songsArtists()->attach($artistId->id);
                }
            }
        }
        
    }
    
    
}

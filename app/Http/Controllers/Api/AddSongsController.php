<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Songs;
use App\Models\Genres;
use App\Models\Artists;
use Illuminate\Database\QueryException;

class AddSongsController extends Controller
{
   public function addSongs(Request $request){
    try{
        $validatedData =  $request->validate([
            'Name' => 'required|string',
            'genres_id' => 'required|string',
            'artists_id'=> 'required|array|min:1',
            'artists_id.*'=> 'string', 
            'publishedDate' => 'date',
        ]);

        $newSongs = new Songs();
        $newSongs->name = $validatedData['Name'];
        $newSongs->genres_id = Genres::firstOrCreate(['Name' => trim($validatedData['genres_id'])])->id;
        $newSongs->publishedDate = $validatedData['publishedDate'];
        $newSongs->save();

        
        // $newArtists = explode(',', $validatedData['artistsName']);
        foreach($validatedData['artists_id'] as $newArtists){
            $artists = Artists::firstOrCreate(['Name' => trim($newArtists)]);
            $newSongs->artists()->attach($artists->id);
        }
        
        return response()->json(['message' => 'Data stored successfully'], 201);
    } catch (QueryException $e) {
        // Handle database query exception, e.g., duplicate entry error
        return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
    } catch (\Exception $e) {
        // Handle other exceptions
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
        
   }
}

// $isNameArtists = $request->input('artistsName');
        // $newArtists = Artists::where('name', $isNameArtists);
        // if(!$newArtists){
        //     $newArtists = new Artists();
        //     $newArtists->artistsName = $validatedData['artistsName'] = $isNameArtists;
        //     $newArtists->save();
        // }

        // $newGenres = new Genres();
        // $newGenres->genresName = $validatedData['genresName'];
        // $newGenres->save();

// $genresName = $request->input('genresName');
//         $genres = Genres::where('name', $genresName)->first();

//         if(!$genres){
//             $genres = new Genres();
//             $genres->genresName = $genresName;
//             $genres->save();
//             return response()->json($genres);
//         }
        

//         // Create a new song instance and associate it with the genre
//         $songs = new Songs();
//         $songs->songsName = $request->input('songs.Name');
//         $songs->genres_id = $genres->id;
//         $songs->save();
//         return response()->json($songs);

//         foreach ($request->input('artistsName') as $artistsData){
//             $artists = new Artists();
//             $artists->nameArtists = $artistsData['Name'];
//             $artists->save();

//             // Attach the artist to the song using the pivot table
//             $songs->artists()->attach($artists->id);
//             return response()->json($artists);
//         }

//         return response()->json([
//             'message' => 'Songs, Artists, and Genres Telah berhasil di input!'
//         ], 201);
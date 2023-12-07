<?php

use App\Http\Controllers\Api\AddSongsController;
use App\Http\Controllers\Api\ApiResourcesController;
use App\Http\Controllers\Api\GenresController;
use App\Http\Controllers\Api\SongsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ArtistsController;
use App\Models\Genres;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('signin', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });
});

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    Route::get('songs/artists',[SongsController::class,'songsArtists'])->name('artists');
    Route::get('songs/artists/{songsId}',[SongsController::class,'songsArtistsSearch'])->name('artists');
    Route::get('songs/genres',[SongsController::class,'songsGenres'])->name('genres');
    Route::get('songs/genres/{genresId}',[songsController::class,'songsGenresSearch'])->name('genres');
    // Route::get('genres',[GenresController::class,'genreses'])->name('genres');
    Route::get('genres/songs',[GenresController::class,'genresSongs'])->name('songs');
    Route::get('genres/songs/{SongssId}',[GenresController::class,'genresSongsSearch'])->name('genres');
    // Route::get('artists',[ArtistsController::class,'showArtists'])->name('artists');
    Route::get('artists/songs',[ArtistsController::class,'artistsSongs'])->name('songs');
    Route::get('artists/songs/{artistsId}',[ArtistsController::class,'artistsSongsSearch'])->name('artists');
    Route::post('addSongs',[AddSongsController::class,'addSongs'])->name('addSongs');
    Route::post('storeArtists',[SongsController::class,'storeArtists'])->name('storeArtists');
});

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    Route::get('{collection}', [ApiResourcesController::class, 'index']);
    Route::get('{collection}/{id}', [ApiResourcesController::class, 'read']);
    Route::post('{collection}', [ApiResourcesController::class, 'create']);
    Route::put('{collection}/{id}', [ApiResourcesController::class, 'update']);
    Route::put('{collection}/{id}/delete', [ApiResourcesController::class, 'softDelete']);
    Route::delete('{collection}/{id}/destroy', [ApiResourcesController::class, 'hardDelete']);
    Route::put('{collection}/{id}/restore', [ApiResourcesController::class, 'restore']);
    // Route::post('addSongs', [ApiResourcesController::class, 'addSongs']);
});

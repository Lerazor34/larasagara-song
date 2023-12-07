<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VocabulariesController;
use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/geoloaction', function () {
    $ip = request()->ip();
    // $location = Location::get('162.222.198.75');
    $location = Location::get($ip);
    if (!$location) return "EN";
    return $location->countryCode;
});

Route::get('/home', function () {
    $page = cache()->remember('home-page', env('CACHE_TIME'), function () {
        $cachedPage = view('web.home')->render();
        return $cachedPage;
    });

    return $page;
});

Route::get('/', function () {
    // $page = cache()->remember('index-page', 60 * 60 * 24, function () {
    //     $cachedPage = view('welcome')->render();
    //     return $cachedPage;
    // });

    // $user = User::find(1);
    // dd($user->role);
    // $role = Roles::with('getPrivilege');
    // $role->getPrivilege;
    // $role->permission;
    // $role->getUser;
    // dd($role->get()->toArray());
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get('lang/{lang}', [LanguageController::class, 'switchLang']);

Route::middleware(['auth', 'permissions'])->group(function () {
    Route::put('/update_password', [UserController::class, 'update_password'])->name('users.update_password');

    Route::prefix('vocabularies')->group(function () {
        Route::post('/generate-language', [VocabulariesController::class, 'generateLanguage'])->name('vocabularies.generate-language');
    });

    Route::controller(UserController::class)
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('create');
            Route::put('/{id}/activation/{status}', 'activation')->name('activation');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('edit');
            Route::get('/profile', 'profile')->name('profile');
            Route::get('/reset_password', 'reset_password')->name('reset_password');
        });

    // Roles
    Route::controller(RoleController::class)
        ->prefix('roles')
        ->name('roles.')
        ->group(function () {
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('edit');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('create');
        });

    // If you want to custome, please write above
    Route::controller(AppController::class)
        ->prefix('{collection}')
        ->name(request()->segment(1) . '.')
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::post('/', 'store')->name('create');
            Route::get('/create', 'create')->name('create');
            Route::get('/trashed', 'trashed')->name('trashed');
            Route::get('/{id}', 'detail')->name('detail');
            Route::put('/{id}', 'update')->name('edit');
            Route::post('/{id}', 'delete')->name('delete');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}/trash', 'trash')->name('trash');
            Route::put('/{id}/restore', 'restore')->name('restore');
        });
});

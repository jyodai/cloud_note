<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NoteSettingController;
use App\Http\Controllers\Api\LibraryFileController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return 'aaaa';
//     return $request->user();
// });

Route::post("users/token", 'Api\UserController@createToken')->name('user.token.store');

Route::middleware(['auth_api'])->group(function () {
    Route::prefix('users')->name('user.')->group(function () {
        Route::delete("/token", 'Api\UserController@deleteToken')->name('token.destroy');

        Route::get('/me', [UserController::class, 'showLoginUser'])->name('me.show');

        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');

        Route::put('/{id}/password', [UserController::class, 'updatePassword'])->name('password.update');
    });

    Route::prefix('notes_settings')->name('notes_settings.')->group(function () {
        Route::get('/', [NoteSettingController::class, 'show'])->name('show');
        Route::put('/{id}', [NoteSettingController::class, 'update'])->name('update');
    });

    Route::prefix('libraries')->group(function () {
        Route::get('/image', [LibraryFileController::class, 'image']);

        Route::get('/', [LibraryFileController::class, 'index']);
        Route::post('/', [LibraryFileController::class, 'store']);
        Route::put('/', [LibraryFileController::class, 'update']);
        Route::delete('/', [LibraryFileController::class, 'destroy']);
    });

    Route::prefix('notes')->name('notes.')->group(function () {
        Route::get('/{id}', 'Api\NoteController@show')->name('show');
        Route::post('/', 'Api\NoteController@store')->name('store');
        Route::put('/{id}', 'Api\NoteController@update')->name('update');
        Route::delete('/{id}', 'Api\NoteController@destroy')->name('destroy');

        Route::get('/{id}/content', 'Api\NoteController@showContent')->name('content.show');
    });

    Route::prefix('note_content')->name('note_content.')->group(function () {
        Route::put('/{id}', 'Api\NoteContentController@update')->name('update');
    });

    Route::prefix('tree')->name('tree.')->group(function () {
        Route::get('/', 'Api\TreeController@index')->name('index');
        Route::get('/{id}/children', 'Api\TreeController@getTreeChildren')->name('children.index');
        Route::put('/{id}/move', 'Api\TreeController@move')->name('move');
    });
});

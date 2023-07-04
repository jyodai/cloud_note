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

Route::post("users/token", 'Api\UserController@createToken');

Route::middleware(['auth_api'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::delete("/token", 'Api\UserController@deleteToken');

        Route::get('/me', [UserController::class, 'showLoginUser']);

        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('notes_settings')->group(function () {
        Route::get('/', [NoteSettingController::class, 'show']);
        Route::put('/{id}', [NoteSettingController::class, 'update']);
    });

    Route::prefix('libraries')->group(function () {
        Route::get('/image', [LibraryFileController::class, 'image']);

        Route::get('/', [LibraryFileController::class, 'index']);
        Route::post('/', [LibraryFileController::class, 'store']);
        Route::put('/', [LibraryFileController::class, 'update']);
        Route::delete('/', [LibraryFileController::class, 'destroy']);
    });

    Route::prefix('notes')->group(function () {
        Route::get('/{id}', 'Api\NoteController@show');
        Route::post('/', 'Api\NoteController@store');
        Route::put('/{id}', 'Api\NoteController@update');
        Route::delete('/{id}', 'Api\NoteController@destroy');

        Route::get('/{id}/content', 'Api\NoteController@showContent');
    });

    Route::put('note_content', 'Api\NoteContentController@save');

    Route::get('tree', 'Api\TreeController@getTree');
    Route::get('tree/{id}/children', 'Api\TreeController@getTreeChildren');
    Route::put('tree/{id}/move', 'Api\TreeController@moveTree');

    Route::get('notes/files', 'Api\Note\FileController@getFile');
    Route::put('notes/files', 'Api\Note\FileController@editFile');
    Route::post('notes/files', 'Api\Note\FileController@addFile');
    Route::delete('notes/files', 'Api\Note\FileController@deleteFile');
});

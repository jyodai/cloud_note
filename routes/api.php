<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\UserController;

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



    Route::get('notes', 'Api\NoteController@getNote');
    Route::put('notes/{id}', 'Api\NoteController@updateNote');
    Route::put('notes', 'Api\NoteController@saveNote');
    Route::post('notes', 'Api\NoteController@addNote');
    Route::delete('notes/{id}', 'Api\NoteController@deleteNote');

    Route::get('note_content', 'Api\NoteContentController@getContent');
    Route::put('note_content', 'Api\NoteContentController@save');

    Route::get('tree', 'Api\TreeController@getTree');
    Route::get('tree/{id}/children', 'Api\TreeController@getTreeChildren');
    Route::put('tree/{id}/move', 'Api\TreeController@moveTree');


    Route::get('libraries/files', 'Api\Library\FileController@getFile');
    Route::put('libraries/files', 'Api\Library\FileController@editFile');
    Route::post('libraries/files', 'Api\Library\FileController@addFile');
    Route::delete('libraries/files', 'Api\Library\FileController@deleteFile');

    Route::get('notes/files', 'Api\Note\FileController@getFile');
    Route::put('notes/files', 'Api\Note\FileController@editFile');
    Route::post('notes/files', 'Api\Note\FileController@addFile');
    Route::delete('notes/files', 'Api\Note\FileController@deleteFile');
});

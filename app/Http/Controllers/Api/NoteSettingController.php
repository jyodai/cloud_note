<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteSetting\UpdateRequest;
use App\Http\Resources\NoteSettingResource;
use App\Models\NoteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteSettingController extends Controller
{
    public function __construct()
    {
    }

    public function show(): NoteSettingResource
    {
        $user        = auth()->user();
        $noteSetting = NoteSetting::where('user_id', $user->id)->first();
        return new NoteSettingResource($noteSetting);
    }

    public function update(int $id, UpdateRequest $request): NoteSettingResource
    {
         NoteSetting::find($id)->update([
             'editor_option' => $request->editor_option,
             'editor_css'    => $request->editor_css,
         ]);
        $noteSetting = NoteSetting::find($id);
        return new NoteSettingResource($noteSetting);
    }
}

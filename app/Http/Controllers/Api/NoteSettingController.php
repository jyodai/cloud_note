<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NoteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NoteSettingResource;

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

    public function update(int $id, Request $request): NoteSettingResource
    {
         NoteSetting::find($id)->update([
             'editor_option' => $request->editor_option,
         ]);
        $noteSetting = NoteSetting::find($id);
        return new NoteSettingResource($noteSetting);
    }
}

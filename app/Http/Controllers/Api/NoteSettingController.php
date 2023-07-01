<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NoteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteSettingController extends Controller
{
    public function __construct()
    {
    }

    public function show(int $id)
    {
        $user        = auth()->user();
        $noteSetting = NoteSetting->where('user_id', $user->id)->first();
        return $noteSetting;
    }

    public function update(int $id, Request $request)
    {
        $noteSetting = NoteSetting::find($id)->update([
            'editor_option' => $request->editor_option,
        ]);
        return $noteSetting;
    }
}

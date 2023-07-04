<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NoteContent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NoteContentController extends Controller
{
    public $user = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function update(int $id, Request $request): Response
    {
        $entity          = NoteContent::find($id);
        $entity->content = $request->content;
        $entity->save();
        return response()->noContent();
    }
}

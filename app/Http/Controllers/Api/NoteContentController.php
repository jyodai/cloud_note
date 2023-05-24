<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\NoteContent;

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
            $this->user= auth()->user();
            return $next($request);
        });
    }

    public function getContent(Request $request)
    {
        $noteId = $request->noteId ? (int) $request->noteId : null;
        $ret = Note::find($noteId)->content;
        return response()->json($ret);
    }

    public function save(Request $request)
    {
        $noteId = (int) $request->noteId;

        if (empty($noteId)) {
            exit;
        }

        $content = is_null($request->content) ? '' : $request->content;

        $entity = NoteContent::where('id', $noteId)->first();
        $entity->content = $content;
        $entity->save();
    }
}

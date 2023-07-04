<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Note;

class TreeController extends Controller
{
    public $user = null;
    public $note = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->note = new Note();
            $this->note->setUser($this->user);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $restoreTree = json_decode($request['tree']);
        $tree        = $this->note->getTree($restoreTree);
        return response()->json($tree);
    }

    public function getTreeChildren(int $id)
    {
        $tree = $this->note->getTree([], $id);
        return response()->json($tree);
    }

    public function moveTree(int $id, Request $request)
    {
        $targetNoteId = $request['target_note_id'];
        $type         = $request['type'];
        $note         = $this->note->moveTree($id, $targetNoteId, $type);
        return $note;
    }
}

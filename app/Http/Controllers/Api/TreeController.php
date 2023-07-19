<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tree\MoveRequest;
use App\Http\Resources\NoteTree\NoteTreeResource;
use App\Http\Resources\Note\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function index(Request $request): AnonymousResourceCollection
    {
        $restoreTree = $request['tree'] ? json_decode($request['tree']) : [];
        $tree        = $this->note->getTree($restoreTree);
        $resources   = NoteTreeResource::collection($tree);
        return $resources;
    }

    public function getTreeChildren(int $id): AnonymousResourceCollection
    {
        $tree      = $this->note->getTree([], $id);
        $resources = NoteTreeResource::collection($tree);
        return $resources;
    }

    public function move(int $id, MoveRequest $request): NoteResource
    {
        $targetNoteId = $request['target_note_id'];
        $type         = $request['type'];
        $note         = $this->note->moveTree($id, $targetNoteId, $type);
        return new NoteResource($note);
    }
}

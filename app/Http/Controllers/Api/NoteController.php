<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\NoteContent;

class NoteController extends Controller
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

    public function show(int $noteId)
    {
        $ret = Note::where('user_id', $this->user->id)
        ->where('id', $noteId)
        ->first();
        return response()->json($ret);
    }

    public function store(Request $request)
    {
        $parentNoteId = (int) $request->parentNoteId;
        $noteTitle    = $request->noteTitle;
        $noteType     = $request->noteType;

        $data           = [
            'parentNoteId' => $parentNoteId,
            'note_type'    => $noteType,
            'title'        => $noteTitle,
            'user_id'      => $this->user->id,
        ];
        $noteEntity     = new Note();
        $note           = $noteEntity->create($data);
        $note->children = [];
        return response()->json($note);
    }

    public function update(int $noteId, Request $request)
    {
        $noteTitle = $request['noteTitle'];

        $entity        = Note::find($noteId);
        $entity->title = $noteTitle ? $noteTitle : $entity->title;
        $entity->save();

        return response()->json($entity);
    }

    public function destroy(int $noteId)
    {
        $deleteNoteId = [];
        $model        = new Note();

        $childNotes = $model->getChildNote($noteId);
        foreach ($childNotes as $childNote) {
            $model->deleteNote($childNote['id']);
            $deleteNoteId[] = $childNote['id'];
        }

        $model->deleteNote($noteId);
        $deleteNoteId[] = $noteId;

        return response()->json([
            'deleteNoteId' => $deleteNoteId,
        ]);
    }
}

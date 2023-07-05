<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\NoteContent;
use App\Http\Requests\Note\StoreRequest;
use App\Http\Requests\Note\UpdateRequest;

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

    public function store(StoreRequest $request)
    {
        $data           = [
            'parent_note_id' => $request->parent_note_id,
            'note_type'      => $request->note_type,
            'title'          => $request->title,
            'user_id'        => $this->user->id,
        ];
        $noteEntity     = new Note();
        $note           = $noteEntity->create($data);
        $note->children = [];
        return response()->json($note);
    }

    public function update(int $noteId, UpdateRequest $request)
    {
        $noteTitle = $request['title'];

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

    public function showContent(int $noteId)
    {
        $ret = Note::find($noteId)->content;
        return response()->json($ret);
    }
}

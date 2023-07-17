<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreRequest;
use App\Http\Requests\Note\UpdateRequest;
use App\Http\Resources\DestroyNoteResource;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\NoteContent;
use Illuminate\Http\Request;

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

    public function show(int $noteId): NoteResource
    {
        $note = Note::where('user_id', $this->user->id)
        ->where('id', $noteId)
        ->first();
        return new NoteResource($note);
    }

    public function store(StoreRequest $request): NoteResource
    {
        $data       = [
            'parent_note_id' => $request->parent_note_id,
            'note_type'      => $request->note_type,
            'title'          => $request->title,
            'user_id'        => $this->user->id,
        ];
        $noteEntity = new Note();
        $note       = $noteEntity->create($data);
        return new NoteResource($note);
    }

    public function update(int $noteId, UpdateRequest $request): NoteResource
    {
        $noteTitle = $request['title'];

        $entity        = Note::find($noteId);
        $entity->title = $noteTitle ? $noteTitle : $entity->title;
        $entity->save();

        return new NoteResource($entity);
    }

    public function destroy(int $noteId): DestroyNoteResource
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

        return new DestroyNoteResource(['delete_note_id' => $deleteNoteId]);
    }

    public function showContent(int $noteId)
    {
        $ret = Note::find($noteId)->content;
        return response()->json($ret);
    }
}

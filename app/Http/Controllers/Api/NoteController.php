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
            $this->user= auth()->user();
            return $next($request);
        });
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getNote(Request $request)
    {
        $ret = null;
        $noteId = $request->noteId ? (int) $request->noteId : null;
        $type = $request->type;
        switch ($type) {
            case 'note':
                $ret = Note::where('user_id', $this->user->id)
                ->where('id', $noteId)
                ->first();
                break;
            case 'content':
                $ret = Note::find($noteId)->content;
                break;
        }
        return response()->json($ret);
    }

    public function saveNote(Request $request)
    {
        $noteId = (int) $request->noteId;

        if (empty($noteId)) {
            exit;
        }

        $type = $request->type;
        switch ($type) {
            case 'note':
                $noteTitle = $request->noteTitle;

                $entity = Note::where('id', $noteId)->first();
                $entity->title = $noteTitle ? $noteTitle : $entity->title;
                $entity->save();
                break;
            case 'content':
                $content = is_null($request->content) ? '' : $request->content;

                $entity = NoteContent::where('id', $noteId)->first();
                $entity->content = $content;
                $entity->save();
                break;
        }
    }

    public function addNote(Request $request)
    {
        $parentNoteId = (int) $request->parentNoteId;
        $noteTitle = $request->noteTitle;

        $data = [
            'parentNoteId' => $parentNoteId,
            'title'    => $noteTitle,
            'user_id'  => $this->user->id,
        ];
        $noteEntity = new Note;
        $note = $noteEntity->create($data);
        $note->children = [];
        return response()->json($note);
    }

    public function updateNote(int $noteId ,Request $request)
    {
        if (empty($noteId)) {
            exit;
        }

        $noteTitle = $request['noteTitle'];

        $entity = Note::find($noteId);
        $entity->title = $noteTitle ? $noteTitle : $entity->title;
        $entity->save();

        return response()->json($entity);
    }

    public function deleteNote(int $noteId)
    {
        $deleteNoteId = [];
        $model = new Note;

        $childNotes =$model->getChildNote($noteId);
        foreach($childNotes as $childNote) {
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

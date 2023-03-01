<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Consts\Note as C_Note;

class NoteContent extends Model
{
     protected $table = 'notes_contents';
     protected $casts = [
        'note_id' => 'integer',
     ];

     public function create($note)
     {
          $this->note_id = $note['id'];
          $this->note_type = C_Note::NOTE_TYPE_NORMAL;
          $this->content = '# ' . $note['title'];
          $this->invalidation_flag = 0;
          $this->save();
     }

     public function note()
     {
        return $this->morphOne(Note::class, 'content', 'note_type', 'note_id');
     }
}

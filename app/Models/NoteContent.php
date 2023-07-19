<?php

namespace App\Models;

use App\Consts\Note as C_Note;
use App\Models\Note;
use Database\Factories\NoteContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteContent extends Model
{
    use HasFactory;

     protected $table = 'notes_contents';
     protected $casts = [
         'note_id' => 'integer',
     ];

     protected static function newFactory()
     {
         return NoteContentFactory::new();
     }

     public function create($note)
     {
          $this->note_id           = $note['id'];
          $this->note_type         = C_Note::NOTE_TYPE_NORMAL;
          $this->content           = '# ' . $note['title'];
          $this->invalidation_flag = 0;
          $this->save();
     }
}

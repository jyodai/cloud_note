<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Note;

class NoteContent extends Model
{
     protected $table = 'notes_contents';
     protected $casts = [
        'note_id' => 'integer',
     ];

     public function create($data)
     {
          $this->note_id = $data['id'];
          $this->content = '# ' . $data['title'];
          $this->invalidation_flag = 0;
          $this->save();
     }

     public function note()
     {
        return $this->morphOne(Note::class, 'content', 'note_type', 'note_id');
     }
}

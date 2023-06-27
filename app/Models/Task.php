<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Consts\Note as C_Note;

class Task extends Model
{
     protected $table = 'tasks';
     protected $casts = [
         'note_id' => 'integer',
     ];

     public function create($note)
     {
          $this->note_id           = $note['id'];
          $this->note_type         = C_Note::NOTE_TYPE_TASK;
          $this->invalidation_flag = 0;
          $this->save();
     }
}

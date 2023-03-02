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
     public $hadDefault = false;

     public function create($data)
     {
          $this->note_id = $date['note_id'];
          $this->note_type = C_Note::NOTE_TYPE_TASK;
          $this->completion_flag = 0;
          $this->register_date = date('Y-m-d');
          $this->invalidation_flag = 0;
          $this->save();
     }

     public function note()
     {
        return $this->morphOne(Note::class, 'content', 'note_type', 'note_id');
     }
}

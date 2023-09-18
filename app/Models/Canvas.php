<?php

namespace App\Models;

use App\Consts\Note as C_Note;
use App\Models\TaskElement;
use Database\Factories\CanvasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canvas extends Model
{
    use HasFactory;

    protected $table    = 'canvases';
    protected $fillable = [
        'content',
    ];

    protected static function newFactory()
    {
        return CanvasFactory::new();
    }

    public function create($note)
    {
        $this->note_id   = $note['id'];
        $this->note_type = C_Note::NOTE_TYPE_CANVAS;
        $this->content   = '{}';
        $this->save();
    }
}

<?php

namespace App\Models;

use App\Models\NoteContent;
use Database\Factories\NoteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table   = 'notes';
    protected $appends = [
        'hasChild',
    ];
    protected $casts   = [
        'parent_note_id' => 'integer',
        'user_id'        => 'integer',
        'display_num'    => 'integer',
        'hierarchy'      => 'integer',
        'path'           => 'array',
    ];

    protected $user = null;

    protected static function newFactory()
    {
        return NoteFactory::new();
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getHasChildAttribute()
    {
        $id = $this->attributes['id'];
        return self::where('parent_note_id', $id)->exists();
    }

    public function content()
    {
        return $this->hasOne($this->note_type, 'note_id');
    }

    public function create($data)
    {
         $path = array_merge(self::getPath($data['parent_note_id']), [$data['title']]);

         $this->parent_note_id    = $data['parent_note_id'];
         $this->user_id           = $data['user_id'];
         $this->note_type         = $data['note_type'];
         $this->title             = $data['title'];
         $this->path              = $path;
         $this->display_num       = $this->nextDisplayNum($data['parent_note_id']);
         $this->hierarchy         = $this->belongHierarchy($data['parent_note_id']);
         $this->invalidation_flag = 0;
         $this->save();

         $this->createAfter();

         return self::find($this->id);
    }

    private function createAfter(): void
    {
         $noteContentEntity = new $this->note_type();
         $noteContentEntity->create($this);

         // 順番がおかしくなっている場合の保険
         $this->adjustOrder($this->parent_note_id);
    }

    public function deleteNote($noteId)
    {
        NoteContent::where('note_id', '=', $noteId)->delete();

        $entity = $this->where('id', $noteId)->first();
        $this->where('id', '=', $noteId)->delete();

       //歯抜けになったdisplay_numを調整
        $this->adjustOrder($entity->parent_note_id);
    }

    /**
     * @brief 親Idを渡すことで、自身の所属する階層を取得
     */
    public function belongHierarchy($parentNoteId)
    {
        if ($parentNoteId === 0) {
            return 1;
        }
        $note = $this->where('id', $parentNoteId)->first();
        return $note->hierarchy + 1;
    }

    /**
     * @brief 親Idを渡すことで、自身の所属する階層の次の表示順序を取得
     */
    public function nextDisplayNum($parentNoteId)
    {
        $notes = $this->where('parent_note_id', $parentNoteId)->get();
        return (count($notes) * 10) + 10;
    }

    /**
     * @brief 配下に所属するノートを取得（再帰的）
     * @return array
     */
    public function getChildNote($id)
    {
        $notes = $this->where('parent_note_id', $id)->get()->toArray();
        $ret   = $notes;
        foreach ($notes as $note) {
            $childNotes = $this->getChildNote($note['id']);
            foreach ($childNotes as $childNote) {
                array_push($ret, $childNote);
            }
        }
        return $ret;
    }

    /**
     * @brief display_numは10刻, 10スタート
     */
    public function adjustOrder($parentNoteId)
    {
        $array = $this->where('parent_note_id', $parentNoteId)
        ->orderBy('display_num', 'asc')
        ->get();
        for ($i = 0; $i < count($array); $i++) {
            $array[$i]['display_num'] = 10 * ($i + 1);
            $array[$i]->save();
        }
    }

    public function getTree($data, $id = 0)
    {
        $allNotes = self::where('user_id', $this->user->id)
                      ->orderBy('display_num', 'asc')
                      ->get();

        $groupedNotes = $allNotes->groupBy('parent_note_id');

        return $this->buildTree($groupedNotes, $data, $id);
    }

    private function buildTree($groupedNotes, $data, $id)
    {
        $collect = $groupedNotes->get($id, collect());
        return $collect->map(
            function ($note) use ($groupedNotes, $data) {
                if (in_array($note->id, $data)) {
                    $note->children = $this->buildTree($groupedNotes, $data, $note->id);
                } else {
                    $note->children = [];
                }

                return $note;
            }
        );
    }

    /**
     * @param array $id 移動対象のノートID
     * @param array $targetNote 移動先のノートID
     */
    public function moveTree($id, $targetNoteId, $type)
    {
        $parentNoteId = null;
        $displayNum   = null;

        $targetNote = self::find($targetNoteId);
        switch ($type) {
            case 'before':
                $parentNoteId = $targetNote->parent_note_id;
                $displayNum   = $targetNote->display_num - 1;
                break;
            case 'after':
                $parentNoteId = $targetNote->parent_note_id;
                $displayNum   = $targetNote->display_num + 1;
                break;
            case 'inside':
                $parentNoteId = $targetNote->id;
                $displayNum   = $this->nextDisplayNum($parentNoteId);
                break;
        }

        $this->adjustOrder($parentNoteId);

        $note                 = self::find($id);
        $note->parent_note_id = $parentNoteId;
        $note->path           = array_merge(self::getPath($parentNoteId), [$note->title]);
        $note->display_num    = $displayNum;
        $note->save();

        $this->adjustOrder($parentNoteId);

        return self::find($id);
    }

    /**
     * @brief デバッグ時に使用する
     */
    public function textTree($id = 0)
    {
        $treeList = $this->where('parent_note_id', $id)->get()->toArray();
        $treeText = $this->convertToTextTree($treeList);
        return $treeText;
    }

    public function convertToTextTree($treeList)
    {
        $ret = '';
        foreach ($treeList as $tree) {
            $space = str_repeat('    ', $tree['hierarchy'] - 1);
            $ret  .= "\n" . $space . ' noteId : ' . $tree['id'] . ', title : ' . $tree['title'];
            if (!$tree['children']) {
                continue;
            }
            $ret .= $this->convertToTextTree($tree['children']);
        }
        return $ret;
    }

    public static function getPath($id, $path = []): array
    {
        $note = self::where('id', $id)->first();
        if (!$note) {
            return $path;
        }

        array_unshift($path, $note->title);
        if ($note->parent_note_id) {
            return self::getPath($note->parent_note_id, $path);
        } else {
            return $path;
        }
    }
}

<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\Note;

class NoteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        $this->createTestData();
    }

    public function testBelongHierarchy()
    {
        $topHierarchy = 1;
        $parentNoteId = 0;
        $note         = new Note();
        $hierarchy    = $note->belongHierarchy($parentNoteId);
        $this->assertSame($hierarchy, $topHierarchy);
    }

    public function testGetTree()
    {
        $data = [0, 1, 2, 3, 14, 19, 22, 23];

        $note = new Note();
        $user = (object) [
            'id' => 1,
        ];
        $note->setUser($user);
        $tree = $note->getTree($data);

        $this->assertSame($tree[0]->id, 1);
        $this->assertSame($tree[0]->children[0]->id, 2);
        $this->assertSame($tree[0]->children[0]->children[0]->id, 3);
        $this->assertSame($tree[0]->children[0]->children[1]->id, 4);
        $this->assertSame($tree[0]->children[0]->children[2]->id, 5);

        $this->assertSame($tree[1]->id, 14);
        $this->assertSame($tree[1]->children[0]->id, 15);
        $this->assertSame($tree[1]->children[0]->children, []);
        $this->assertSame($tree[1]->children[1]->id, 19);
        $this->assertSame($tree[1]->children[1]->children[0]->id, 20);
        $this->assertSame($tree[1]->children[1]->children[1]->id, 21);
        $this->assertSame($tree[1]->children[1]->children[2]->id, 22);
        $this->assertSame($tree[1]->children[2]->id, 23);
        $this->assertSame($tree[1]->children[2]->children[0]->id, 24);
        $this->assertSame($tree[1]->children[2]->children[1]->id, 25);
        $this->assertSame($tree[1]->children[2]->children[2]->id, 26);

        $this->assertSame($tree[2]->id, 27);
        $this->assertSame($tree[2]->children, []);
    }

    public function createTestData($parentNoteId = 0, $hierarchy = 1)
    {
        for ($i = 0; $i < 3; $i++) {
            $data = [
                'parentNoteId' => $parentNoteId,
                'user_id'      => 1,
                'title'        => 'hoge',
            ];
            $note = new Note();
            $createNote = $note->create($data);
            $createNote->title = $createNote->id;
            $createNote->save();

            if ($hierarchy < 3) {
                $nextHierarchy = $hierarchy + 1;
                $this->createTestData($createNote->id, $nextHierarchy);
            }
        }
    }

/**
 * [テストデータの構成]
 * 
 * createTestDataメソッドで生成されるデータ
 * 
 * noteId : 1, title : 1
 *     noteId : 2, title : 2
 *         noteId : 3, title : 3
 *         noteId : 4, title : 4
 *         noteId : 5, title : 5
 *     noteId : 6, title : 6
 *         noteId : 7, title : 7
 *         noteId : 8, title : 8
 *         noteId : 9, title : 9
 *     noteId : 10, title : 10
 *         noteId : 11, title : 11
 *         noteId : 12, title : 12
 *         noteId : 13, title : 13
 * noteId : 14, title : 14
 *     noteId : 15, title : 15
 *         noteId : 16, title : 16
 *         noteId : 17, title : 17
 *         noteId : 18, title : 18
 *     noteId : 19, title : 19
 *         noteId : 20, title : 20
 *         noteId : 21, title : 21
 *         noteId : 22, title : 22
 *     noteId : 23, title : 23
 *         noteId : 24, title : 24
 *         noteId : 25, title : 25
 *         noteId : 26, title : 26
 * noteId : 27, title : 27
 *     noteId : 28, title : 28
 *         noteId : 29, title : 29
 *         noteId : 30, title : 30
 *         noteId : 31, title : 31
 *     noteId : 32, title : 32
 *         noteId : 33, title : 33
 *         noteId : 34, title : 34
 *         noteId : 35, title : 35
 *     noteId : 36, title : 36
 *         noteId : 37, title : 37
 *         noteId : 38, title : 38
 *         noteId : 39, title : 39
 */

}

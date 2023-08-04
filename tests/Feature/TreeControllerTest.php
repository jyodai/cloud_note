<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\NoteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TreeControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $headers;
    private $note;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->headers = ['Authorization' => 'Bearer ' . $this->user->api_token];

        $this->note = $this->createNote();
    }

    private function createNote(): Note
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);
        NoteContent::factory()->create([
            'note_id'   => $note->id,
            'note_type' => $note->note_type,
        ]);
        return $note;
    }

    public function testIndex()
    {
        $response = $this->withHeaders($this->headers)->getJson(route('tree.index'));

        $response->assertStatus(200);
    }

    public function testGetTreeChildren()
    {
        $response = $this->withHeaders($this->headers)->getJson(route('tree.children.index', $this->note->id));

        $response->assertStatus(200);
    }

    public function testMove()
    {
        $note      = Note::factory()->create(['user_id' => $this->user->id, 'parent_note_id' => $this->note->id]);
        $childNote = Note::factory()->create(['user_id' => $this->user->id, 'parent_note_id' => $note->id]);

        $targetNote = Note::factory()->create(['user_id' => $this->user->id, 'parent_note_id' => $this->note->id]);

        $response     = $this->withHeaders($this->headers)->putJson(route('tree.move', $note->id), [
            'target_note_id' => $targetNote->id,
            'type'           => 'inside',
        ]);
        $responseData = $response->json();

        $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'parent_note_id' => $targetNote->id,
                     'path'           => Note::getPath($responseData['data']['id']),
                 ],
             ]);

        $updatedChildNote = Note::find($childNote->id);
        $this->assertEquals($updatedChildNote->path, Note::getPath($updatedChildNote->id));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\NoteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteContentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->headers = ['Authorization' => 'Bearer ' . $this->user->api_token];
    }

    public function testUpdate()
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $noteContent = NoteContent::factory()->create([
            'note_id'   => $note->id,
            'note_type' => $note->note_type,
        ]);

        $data = [
            'content' => $this->faker->paragraph,
        ];

        $response = $this->withHeaders($this->headers)->putJson(route('note_content.update', $noteContent->id), $data);

        $response->assertStatus(204);

        $this->assertDatabaseHas('notes_contents', [
            'id'      => $noteContent->id,
            'content' => $data['content'],
        ]);
    }
}

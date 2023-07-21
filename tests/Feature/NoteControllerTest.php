<?php

namespace Tests\Feature;

use App\Consts\Note as C_Note;
use App\Models\Note;
use App\Models\NoteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteControllerTest extends TestCase
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

    public function testShow()
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders($this->headers)->getJson(route('notes.show', $note->id));

        $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'id'    => $note->id,
                     'title' => $note->title,
                     'path'  => $note->path,
                 ],
             ]);
    }

    public function testStore()
    {
        $data = [
            'parent_note_id' => 0,
            'note_type'      => C_Note::NOTE_TYPE_NORMAL,
            'title'          => $this->faker->sentence,
        ];

        $response     = $this->withHeaders($this->headers)->postJson(route('notes.store'), $data);
        $responseData = $response->json();

        $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'title' => $data['title'],
                     'path'  => Note::getPath($responseData['data']['id']),
                 ],
             ]);
    }

    public function testStoreWithNestedNote()
    {
        $parentNote = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $data = [
            'parent_note_id' => $parentNote->id,
            'note_type'      => C_Note::NOTE_TYPE_NORMAL,
            'title'          => $this->faker->sentence,
        ];

        $response     = $this->withHeaders($this->headers)->postJson(route('notes.store'), $data);
        $responseData = $response->json();

        $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'parent_note_id' => $parentNote->id,
                     'title'          => $data['title'],
                     'path'           => Note::getPath($responseData['data']['id']),
                 ],
             ]);
    }

    public function testUpdate()
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $data = [
            'title' => $this->faker->sentence,
        ];

        $response = $this->withHeaders($this->headers)->putJson(route('notes.update', $note->id), $data);

        $response->assertStatus(200);
    }

    public function testUpdateWithNestedNote()
    {
        $parentNote = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $title = $this->faker->sentence;

        $note = Note::factory()->create([
            'parent_note_id' => $parentNote->id,
            'user_id'        => $this->user->id,
            'title'          => $title,
            'path'           => array_merge(Note::getPath($parentNote->id), [$title]),
        ]);

        $data = [
            'title' => $this->faker->sentence,
        ];

        $response     = $this->withHeaders($this->headers)->putJson(route('notes.update', $note->id), $data);
        $responseData = $response->json();

        $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'parent_note_id' => $parentNote->id,
                     'title'          => $data['title'],
                     'path'           => Note::getPath($responseData['data']['id']),
                 ],
             ]);
    }

    public function testDestroy()
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders($this->headers)->deleteJson(route('notes.destroy', $note->id));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'delete_note_id' => [$note->id],
                ],
            ]);
    }

    public function testShowContent()
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
        ]);

        NoteContent::factory()->create([
            'note_id'   => $note->id,
            'note_type' => $note->note_type,
        ]);

        $response = $this->withHeaders($this->headers)->getJson(route('notes.content.show', $note->id));

        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Models\NoteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteSettingControllerTest extends TestCase
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
        NoteSetting::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->headers)->getJson(route('notes_settings.show'));

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $noteSetting = NoteSetting::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'editor_option' => "{ 'keyMap' : 'vim' }",
            'editor_css'    => ' h1 { color : red; }',
        ];

        $response = $this->withHeaders($this->headers)->putJson(
            route('notes_settings.update', $noteSetting->id),
            $data
        );

        $response->assertStatus(200);
    }
}

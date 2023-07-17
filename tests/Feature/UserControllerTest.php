<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->user->api_token = Str::random(60);
        $this->user->save();

        $this->headers = ['Authorization' => 'Bearer ' . $this->user->api_token];
    }

    public function testIndex()
    {
        $response = $this->withHeaders($this->headers)->getJson(route('user.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                    ],
                ],
            ]);
    }

    public function testShow()
    {
        $response = $this->withHeaders($this->headers)->getJson(route('user.show', $this->user->id));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'email' => $this->user->email,
                ],
            ]);
    }

    public function testStore()
    {
        $data = [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->unique()->safeEmail,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->withHeaders($this->headers)->postJson(route('user.store'), $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function testUpdate()
    {
        $data = [
            'name'  => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];

        $response = $this->withHeaders($this->headers)->putJson(route('user.update', $this->user->id), $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'    => $this->user->id,
                    'name'  => $data['name'],
                    'email' => $data['email'],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id'    => $this->user->id,
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function testDestroy()
    {
        $response = $this->withHeaders($this->headers)->deleteJson(route('user.destroy', $this->user->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
        ]);
    }

    public function testShowLoginUser()
    {
        $response = $this->withHeaders($this->headers)->getJson(route('user.me.show'));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'email' => $this->user->email,
                ],
            ]);
    }

    public function testUpdatePassword()
    {
        $data = [
            'password' => 'hoge',
        ];

        $response = $this->withHeaders($this->headers)->putJson(
            route('user.password.update', ['id' => $this->user->id]),
            $data
        );

        $response->assertStatus(204);
    }

    public function testCreateToken()
    {
        $data = [
            'email'    => $this->user->email,
            'password' => 'password',
        ];

        $response = $this->postJson(route('user.token.store'), $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'email' => $this->user->email,
                ],
            ]);
    }

    public function testDeleteToken()
    {
        $response = $this->withHeaders($this->headers)->deleteJson(route('user.token.destroy'));

        $response->assertStatus(204);
    }
}

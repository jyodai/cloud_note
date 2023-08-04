<?php

namespace Tests\Feature;

use App\Consts\Note as C_Note;
use App\Models\Note;
use App\Models\Task;
use App\Models\TaskElement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
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

    private function createNote(): Note
    {
        return Note::factory()->create([
            'user_id'   => $this->user->id,
            'note_type' => C_Note::NOTE_TYPE_TASK,
        ]);
    }

    private function createTask(Note $note): Task
    {
        return Task::factory()->create([
            'note_id' => $note->id,
        ]);
    }

    public function testGetTree()
    {
        $note = $this->createNote();
        $task = $this->createTask($note);

        $taskElementA = TaskElement::factory()->create([
            'task_id' => $task->id,
        ]);

        $taskElementB = TaskElement::factory()->create([
            'task_id' => $task->id,
        ]);

        $taskElementChild = TaskElement::factory()->create([
            'task_id'                => $task->id,
            'parent_task_element_id' => $taskElementA->id,
            'hierarchy'              => 2,
        ]);

        $response     = $this->withHeaders($this->headers)->getJson(route('tasks.tree.show', $task->id));
        $responseData = $response->json();

        $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     [
                         'id'       => $taskElementA->id,
                         'children' => [
                             [
                                 'id' => $taskElementChild->id,
                             ],
                         ],
                     ],
                     [
                         'id' => $taskElementB->id,
                     ],
                 ],
             ]);
    }
}

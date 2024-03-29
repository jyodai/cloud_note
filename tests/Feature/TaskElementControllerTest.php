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

class TaskElementControllerTest extends TestCase
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

    public function testShow()
    {
        $note        = $this->createNote();
        $task        = $this->createTask($note);
        $taskElement = TaskElement::factory()->create([
            'task_id' => $task->id,
        ]);

        $response = $this->withHeaders($this->headers)->getJson(
            route('tasks.elements.show', $taskElement->id)
        );

        $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'id' => $taskElement->id,
                 ],
             ]);
    }

    public function testStore()
    {
        $note = $this->createNote();
        $task = $this->createTask($note);

        $dataA = [
            'task_id'                => $task->id,
            'parent_task_element_id' => 0,
            'name'                   => $this->faker->sentence,
        ];

        $response     = $this->withHeaders($this->headers)->postJson(route('tasks.elements.store'), $dataA);
        $responseData = $response->json();

        $response->assertStatus(201)
             ->assertJson([
                 'data' => [
                     'name'        => $dataA['name'],
                     'display_num' => 10,
                     'hierarchy'   => 1,
                 ],
             ]);

        $dataB = [
            'task_id'                => $task->id,
            'parent_task_element_id' => 0,
            'name'                   => $this->faker->sentence,
        ];

        $response     = $this->withHeaders($this->headers)->postJson(route('tasks.elements.store'), $dataB);
        $responseData = $response->json();

        $response->assertStatus(201)
             ->assertJson([
                 'data' => [
                     'name'        => $dataB['name'],
                     'display_num' => 20,
                     'hierarchy'   => 1,
                 ],
             ]);
    }

    public function testUpdate()
    {
        $note        = $this->createNote();
        $task        = $this->createTask($note);
        $taskElement = TaskElement::factory()->create([
            'task_id' => $task->id,
            'name'    => $this->faker->sentence,
        ]);

        $name       = $this->faker->sentence;
        $content    = $this->faker->paragraph;
        $stargeDate = now()->format('Y-m-d');
        $dueDate    = now()->format('Y-m-d');
        $data       = [
            'name'       => $name,
            'content'    => $content,
            'start_date' => $stargeDate,
            'due_date'   => $dueDate,
        ];

        $response = $this->withHeaders($this->headers)
            ->putJson(route('tasks.elements.update', $taskElement->id), $data);

        $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'name'       => $name,
                     'content'    => $content,
                     'start_date' => $stargeDate,
                     'due_date'   => $dueDate,
                 ],
             ]);
    }

    public function testDestory()
    {
        $note = $this->createNote();
        $task = $this->createTask($note);

        $taskElementA = TaskElement::factory()->create([
            'task_id' => $task->id,
        ]);

        $taskElementB = TaskElement::factory()->create([
            'task_id'                => $task->id,
            'parent_task_element_id' => $taskElementA->id,
            'hierarchy'              => 2,
        ]);

        $taskElementC = TaskElement::factory()->create([
            'task_id'                => $task->id,
            'parent_task_element_id' => $taskElementB->id,
            'hierarchy'              => 3,
        ]);

        $response = $this->withHeaders($this->headers)->deleteJson(
            route('tasks.elements.destroy', $taskElementA->id)
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks_elements', ['id' => $taskElementA->id]);
        $this->assertDatabaseMissing('tasks_elements', ['id' => $taskElementB->id]);
        $this->assertDatabaseMissing('tasks_elements', ['id' => $taskElementC->id]);
    }
}

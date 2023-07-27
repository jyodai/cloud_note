<?php

namespace Database\Factories;

use App\Models\TaskElement;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskElementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskElement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $task = Task::all()->random();

        return [
            'task_id'                => $task->id,
            'parent_task_element_id' => 0,
            'name'                   => $this->faker->word,
            'content'                => $this->faker->sentence,
            'display_num'            => 10,
            'hierarchy'              => 1,
            'completion_flag'        => 0,
            'register_date'          => now()->format('Y-m-d'),
            'start_date'             => null,
            'due_date'               => null,
            'completion_date'        => null,
            'invalidation_flag'      => 0,
        ];
    }
}

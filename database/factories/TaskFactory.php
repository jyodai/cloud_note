<?php

namespace Database\Factories;

use App\Consts\Note as C_Note;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'note_id'           => null,
            'note_type'         => C_Note::NOTE_TYPE_TASK,
            'invalidation_flag' => 0,
        ];
    }
}

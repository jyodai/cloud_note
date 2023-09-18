<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Canvas;
use Illuminate\Database\Eloquent\Factories\Factory;

class CanvasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Canvas::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $note = Note::all()->random();

        return [
            'note_id'   => $note->id,
            'note_type' => $note->note_type,
            'content'   => json_encode([$this->faker->paragraph]),
        ];
    }
}

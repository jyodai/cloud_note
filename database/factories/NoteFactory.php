<?php

namespace Database\Factories;

use App\Consts\Note as C_Note;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user       = User::all()->random();
        $parentNote = null;
        if (Note::exists()) {
            $parentNote = Note::all()->random();
        }

        return [
            'parent_note_id'    => $parentNote ? $parentNote->id : 0,
            'user_id'           => $user->id,
            'note_type'         => C_Note::NOTE_TYPE_NORMAL,
            'title'             => $this->faker->sentence,
            'display_num'       => $this->faker->randomDigit,
            'hierarchy'         => $parentNote ? $parentNote->hierarchy + 1 : 1,
            'invalidation_flag' => 0,
        ];
    }
}

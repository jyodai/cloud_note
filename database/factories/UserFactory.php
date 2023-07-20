<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Consts\User as C_User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'      => $this->faker->name,
            'email'     => $this->faker->unique()->safeEmail,
            'user_type' => C_User::USER_TYPE_ADMIN,
            'password'  => Hash::make('password'),
            'api_token' => Str::random(60),
        ];
    }
}

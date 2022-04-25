<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mobile'=>'+989'.random_int(1111,9999).random_int(11111,99999),
            'type'=>User::USER_TYPE,
            'email'=>$this->faker->safeEmail,
            'name'=>$this->faker->userName,
            'password'=>bcrypt('1234'),
            'avatar'=>null,
            'website'=>$this->faker->url,
            'verify_code'=>null,
            'verified_at'=>now(),
        ];
    }


    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => User::ADMIN_TYPE,
            ];
        });
    }
}

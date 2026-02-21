<?php

namespace Database\Factories;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evenement>
 */
class EvenementFactory extends Factory
{
    protected $model = Evenement::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'date' => fake()->dateTimeBetween('now', '+3 months'),
            'heure' => fake()->time('H:i'),
            'lieu' => fake()->city(),
            'user_id' => User::factory(),
            'diffusion_id' => 1,
            'max_participants' => fake()->optional(0.7)->numberBetween(5, 50),
        ];
    }
}

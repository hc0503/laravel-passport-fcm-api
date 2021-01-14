<?php

namespace Database\Factories;

use App\Models\GigItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class GigItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GigItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->title,
            'description' => $this->faker->company
        ];
    }
}

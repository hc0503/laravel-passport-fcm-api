<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'type' => 'PERFORMANCE',
            'notification_type' => 'FOLLOW',
            'title' => $this->faker->title,
            'body' => $this->faker->company,
            'is_archive' => 0
        ];
    }
}

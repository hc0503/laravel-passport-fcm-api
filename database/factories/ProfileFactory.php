<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'type' => 'PERFORMER',
            'cover_photo' => 'https://firebase.google.com/images/social.png',
            'profile_photo' => 'https://firebase.google.com/images/social.png',
            'stage_name' => $this->faker->userName,
            'about_you' => $this->faker->company,
            'categories' => json_encode(['MUSIC', 'MAGIC', 'CHATING']),
            'tags' => json_encode(['LARAVEL', 'VUEJS', 'DJANGO']),
            'name' => $this->faker->name,
            'interested_in' => json_encode(['MAGIC', 'CHATING']),
            'organization_type' => 'INDIVIDUAL',
        ];
    }
}

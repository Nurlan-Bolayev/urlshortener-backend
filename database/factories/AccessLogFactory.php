<?php

namespace Database\Factories;

use App\Models\AccessLog;
use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccessLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccessLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url_id' => Url::factory(),
            'user_agent' => $this->faker->userAgent,
        ];
    }
}

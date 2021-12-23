<?php

namespace Database\Factories;

use App\Models\post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = post::class;

    public function definition(): array
    {
    	return [
    	    'title'=>$this->faker->sentence(),
            'description'=>$this->faker->paragraph(),
    	];
    }
}

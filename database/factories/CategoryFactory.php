<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // ir al folder config y luego al file filesystems para cambiar el local por public
            'image' => 'categories/' . $this->faker->image('public/storage/categories', 640, 480, null, false), // solo el nombre del producto
        ];
    }
}

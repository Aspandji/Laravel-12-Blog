<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Post::class;

    public function definition(): array
    {
        $isPublished = $this->faker->boolean(70);

        return [
            'category_id' => Category::inRandomOrder()->value('id'),
            'title' => $this->faker->sentence(6),
            'slug' => fn(array $attr) => Str::slug($attr['title']) . '-' . Str::random(5),
            'excerpt' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(5, true),
            'featured_image' => null,

            'is_published' => $isPublished,

            // TANGGAL RANDOM
            'published_at' => $isPublished
                ? $this->faker->dateTimeBetween('-3 months', 'now')
                : null,

            // SEO sengaja null
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
        ];
    }
}

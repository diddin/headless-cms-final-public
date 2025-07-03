<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // public function definition(): array
    // {
    //     $title = $this->faker->sentence();

    //     return [
    //         'title' => $title,
    //         'slug' => Str::slug($title),
    //         'content' => $this->faker->paragraphs(3, true),
    //         'excerpt' => $this->faker->sentence(),
    //         'image' => $this->faker->imageUrl(640, 480, 'post', true),
    //         'status' => $this->faker->randomElement(['draft', 'published']),
    //         'published_at' => now(),
    //     ];
    // }
    public function definition(): array
    {
        $titleEn = $this->faker->sentence();
        $titleId = $this->faker->sentence();

        $contentEn = $this->faker->paragraphs(3, true);
        $contentId = $this->faker->paragraphs(3, true);

        $excerptEn = $this->faker->sentence();
        $excerptId = $this->faker->sentence();
        
        return [
            'title' => json_encode([
                'en' => $titleEn,
                'id' => $titleId,
            ]),
            'slug' => Str::slug($titleEn),
            'content' => json_encode([
                'en' => $contentEn,
                'id' => $contentId,
            ]),
            'excerpt' => json_encode([
                'en' => $excerptEn,
                'id' => $excerptId,
            ]),
            'image' => $this->faker->imageUrl(640, 480, 'post', true),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'published_at' => now(),

            // ...
        ];
    }
}

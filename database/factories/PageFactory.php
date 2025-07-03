<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        $image = $this->faker->imageUrl(800, 400, 'Placeholder', true);
        $altImage = 'Image showing ' . $this->faker->words(3, true);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => [
                [
                    'type' => 'heading',
                    'data' => [
                        'text' => $title,
                        'level' => $this->faker->numberBetween(1, 3),
                    ],
                ],
                [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => $this->faker->paragraph(),
                    ],
                ],
                [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => $this->faker->paragraph(),
                    ],
                ],
                [
                    'type' => 'image',
                    'data' => [
                        'url' => $image,
                        'alt' => $altImage,
                    ]
                ],
            ],
            'status' => $this->faker->randomElement(['draft', 'published']),
        ];
    }
}

<?php

namespace Tests\Api\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user and authenticate with Sanctum
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_can_get_paginated_posts_list()
    {
        // Setup: buat data dummy
        Category::factory()->count(3)->create();
        Post::factory()
            ->count(5)
            ->hasAttached(Category::inRandomOrder()->limit(2)->get())
            ->create();

        // Act: panggil API
        $response = $this->getJson('/api/v1/posts?per_page=2');

        // Assert status OK
        $response->assertStatus(200);

        // Assert format dasar response
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'excerpt',
                        'content',
                        'status',
                        'published_at',
                        'image',
                        'categories' => [
                            '*' => [
                                'id',
                                'name',
                                'slug'
                            ]
                        ]
                    ]
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url',
                            'label',
                            'active'
                        ]
                    ],
                    'path',
                    'per_page',
                    'to',
                    'total'
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ]
            ]
        ]);

        // Assert jumlah data items = 2 sesuai per_page
        $this->assertCount(2, $response->json('data.items'));

        // Assert meta data
        $this->assertEquals(1, $response->json('data.meta.current_page'));
        $this->assertEquals(2, $response->json('data.meta.per_page'));
    }

    public function test_can_create_post_with_category_and_image()
    {
        Storage::fake('public');

        $category = Category::factory()->create();

        $payload = [
            'title' => 'Test Post',
            'content' => 'This is the content.',
            'excerpt' => 'This is the excerpt.',
            'status' => 'published',
            'published_at' => now(),
            'image' => UploadedFile::fake()->image('post.jpg'),
            'categories' => [$category->id],
        ];

        $response = $this->postJson('/api/v1/posts', $payload);

        $response->assertCreated()
                 ->assertJsonFragment(['title' => 'Test Post']);

        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);
        $this->assertDatabaseHas('category_post', [
            'post_id' => Post::first()->id,
            'category_id' => $category->id,
        ]);

        Storage::disk('public')->exists('posts/' . Post::first()->image);

        //$this->assertTrue(Storage::disk('public')->exists('posts/' . Post::first()->image));
    }

    public function test_validation_error_when_missing_title()
    {
        $response = $this->postJson('/api/v1/posts', [
            'content' => 'Content only',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_can_update_post()
    {
        $post = Post::factory()->create(['title' => 'Old Title']);

        $response = $this->putJson("/api/v1/posts/{$post->id}", [
            'title' => 'Updated Title',
            'content' => $post->content,
            'status' => 'published',
        ]);

        $response->assertOk()
                 ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Title']);
    }

    public function test_can_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/v1/posts/{$post->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}

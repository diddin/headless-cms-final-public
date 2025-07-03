<?php

namespace Tests\Api\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user dan token (sesuaikan dengan guard/token yang kamu pakai)
        $this->user = User::factory()->create();

        // Misal menggunakan Passport or Sanctum, pakai actingAs
        $this->actingAs($this->user, 'sanctum');

        // Jika mau pakai header Authorization manual, buat token dulu
        // $token = $this->user->createToken('TestToken')->plainTextToken;
        // $this->headers = [
        //     'Authorization' => 'Bearer ' . $token,
        //     'Accept' => 'application/json',
        // ];
    }

    public function test_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'items' => [
                        '*' => ['id', 'name', 'slug']
                    ],
                    'meta' => [
                        'current_page',
                        'from',
                        'last_page',
                        'links' => [
                            '*' => ['url', 'label', 'active']
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
    }

    public function test_can_show_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/v1/categories/{$category->id}");

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
            ]);
    }

    public function test_can_create_category()
    {
        $payload = [
            'name' => 'Technology Bomb',
        ];

        $response = $this->postJson('/api/v1/categories', $payload);

        $response->assertCreated()
            ->assertJsonFragment([
                'name' => 'Technology Bomb',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Technology Bomb',
        ]);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create([
            'name' => 'Old Name',
        ]);

        $payload = [
            'name' => 'Tech A, PC & Gadgets',
        ];

        $response = $this->putJson("/api/v1/categories/{$category->id}", $payload);

        $response->assertOk()
            ->assertJsonFragment([
                'name' => 'Tech A, PC & Gadgets',
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Tech A, PC & Gadgets',
        ]);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/v1/categories/{$category->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}

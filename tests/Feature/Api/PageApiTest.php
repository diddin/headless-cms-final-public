<?php

namespace Tests\Api\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Page;
use App\Models\User;

class PageApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticatedHeader()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }

    public function test_can_list_pages()
    {
        Page::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/pages', $this->authenticatedHeader());

        $response->assertOk()
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'items' => [
                             '*' => ['id', 'title', 'slug', 'body', 'status']
                         ],
                         'meta',
                         'links',
                     ]
                 ]);
    }

    public function test_can_show_single_page()
    {
        $page = Page::factory()->create();

        $response = $this->getJson("/api/v1/pages/{$page->id}", $this->authenticatedHeader());

        $response->assertOk()
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['id', 'title', 'slug', 'body', 'status']
                 ]);
    }

    public function test_can_create_page()
    {
        $payload = [
            'title' => 'About Us',
            'body' => [
                ['type' => 'heading', 'data' => ['text' => 'About Us', 'level' => 2]],
                ['type' => 'paragraph', 'data' => ['text' => 'This is the about us page.']],
            ],
            'status' => 'published',
        ];

        $response = $this->postJson('/api/v1/pages', $payload, $this->authenticatedHeader());

        $response->assertCreated()
                ->assertJsonFragment(['title' => 'About Us']);

        $this->assertDatabaseHas('pages', ['title' => 'About Us']);
    }

    public function test_can_update_page()
    {
        $page = Page::factory()->create();

        $payload = [
            'title' => 'Updated About Page',
            'body' => [
                ['type' => 'paragraph', 'data' => ['text' => 'Updated content here']],
            ],
            'status' => 'draft',
        ];

        $response = $this->putJson("/api/v1/pages/{$page->id}", $payload, $this->authenticatedHeader());

        $response->assertOk()
                ->assertJsonFragment(['title' => 'Updated About Page']);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Updated About Page'
        ]);
    }

    public function test_can_delete_page()
    {
        $page = Page::factory()->create();

        $response = $this->deleteJson("/api/v1/pages/{$page->id}", [], $this->authenticatedHeader());

        $response->assertNoContent();

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }
}

<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostData
{
    /**
     * Data Transfer Object for Post data.
     *
     * @property array $title The title of the post.
     * @property string $slug The slug (URL-friendly identifier) of the post.
     * @property array $content The main content of the post.
     * @property array|null $excerpt An optional short summary or excerpt of the post.
     * @property string|null $image An optional URL or path to the post's featured image.
     * @property string $status The status of the post (default is 'draft').
     * @property string|null $published_at An optional publication date and time for the post.
     * @property array|null $categories An optional array of categories associated with the post.
     */
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly array $title,
        public readonly string $slug,
        public readonly array $content,
        public readonly ?array $excerpt = null,
        public readonly ?string $image = null,
        public readonly string $status = 'draft',
        public readonly ?string $published_at = null,
        public readonly ?array $categories = []
    ) {}

    /**
     * Create a new instance of the class from an array of data.
     *
     * @param array $data The input data array containing keys:
     *                    - 'title' (string): The title of the post.
     *                    - 'slug' (string): The slug for the post.
     *                    - 'content' (string): The content of the post.
     *                    - 'excerpt' (string|null): The excerpt of the post (optional).
     *                    - 'image' (string|null): The image URL for the post (optional).
     *                    - 'status' (string): The status of the post, defaults to 'draft' (optional).
     *                    - 'published_at' (\DateTimeInterface|null): The publication date of the post (optional).
     *                    - 'categories' (array): The categories associated with the post, defaults to an empty array (optional).
     *
     * @return self Returns an instance of the class populated with the provided data.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'],
            content: $data['content'],
            excerpt: $data['excerpt'] ?? null,
            image: $data['image'] ?? null,
            status: $data['status'] ?? 'draft',
            published_at: $data['published_at'] ?? null,
            categories: $data['categories'] ?? []
        );
    }

    /**
     * Creates an instance of the class from the given HTTP request.
     *
     * This method extracts validated data from the request, generates a slug
     * based on the title, and handles the image file upload if present.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the data.
     * 
     * @return self Returns an instance of the class populated with the request data.
     *
     * @throws \Illuminate\Validation\ValidationException If the request validation fails.
     */
    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['title']['en'] ?? $data['title']['id'] ?? 'untitled');

        if ($request->hasFile('image')) {
            $disk = config('filesystems.default');
            $data['image'] = $request->file('image')->store('posts', $disk);
        }

        return self::fromArray($data);
    }

    /**
     * Creates an instance of PostData from a request for updating a post.
     *
     * This method extracts and validates data from the given request, processes
     * the slug based on the title if available, handles the image upload if present,
     * and initializes a new PostData object with the processed data.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing post data.
     * 
     * @return self A new instance of PostData populated with the request data.
     *
     * @throws \Illuminate\Validation\ValidationException If the request validation fails.
     */
    public static function fromRequestForUpdate(Request $request): self
    {
        $data = $request->validated();

        $data['slug'] = isset($data['title']['en']) 
            ? Str::slug($data['title']['en'])
            : ($data['slug'] ?? '');

        if ($request->hasFile('image')) {
            $disk = config('filesystems.default');
            $data['image'] = $request->file('image')->store('posts', $disk);
        }

        return new self(
            title: $data['title'] ?? '',
            slug: $data['slug'] ?? '',
            content: $data['content'] ?? '',
            excerpt: $data['excerpt'] ?? null,
            image: $data['image'] ?? null,
            status: $data['status'] ?? 'draft',
            published_at: $data['published_at'] ?? null,
            categories: $data['categories'] ?? []
        );
    }
}

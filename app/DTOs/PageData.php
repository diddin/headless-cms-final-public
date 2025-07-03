<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageData
{
    /**
     * Data Transfer Object for Page Data.
     *
     * @property-read string $title The title of the page.
     * @property-read string $slug The slug (URL-friendly identifier) of the page.
     * @property-read array $body The content blocks of the page.
     * @property-read string $status The status of the page, defaulting to 'draft'.
     */
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $title,
        public readonly string $slug,
        public readonly array $body,
        public readonly string $status = 'draft',
    ) {}

    /**
     * Creates a new instance of the class from an associative array.
     *
     * @param array $data The input data array containing keys 'title', 'slug', 'body', and optionally 'status'.
     *                    - 'title' (string): The title of the page.
     *                    - 'slug' (string): The slug for the page.
     *                    - 'body' (string): The body content of the page.
     *                    - 'status' (string, optional): The status of the page. Defaults to 'draft' if not provided.
     *
     * @return self Returns an instance of the class populated with the provided data.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'],
            body: $data['body'],
            status: $data['status'] ?? 'draft',
        );
    }

    /**
     * Create a new instance of the class from a validated request.
     *
     * This method extracts validated data from the given request, generates a slug
     * based on the title, and creates an instance of the class using the data.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing validated data.
     * 
     * @return self A new instance of the class populated with the request data.
     */
    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);

        return self::fromArray($data);
    }

    /**
     * Creates an instance of the PageData DTO from a validated request for update.
     *
     * This method extracts validated data from the given request and optionally
     * generates a slug based on the title if it is present in the data.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing validated data.
     * 
     * @return self Returns an instance of the PageData DTO populated with the provided data.
     */
    public static function fromRequestForUpdate(Request $request): self
    {
        $data = $request->validated();

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return self::fromArray($data);
    }
}

<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryData
{
    /**
     * Data Transfer Object for Category data.
     *
     * @param string $name The name of the category.
     * @param string $slug The slug identifier for the category.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
    ) {}

    /**
     * Creates a new instance of the CategoryData DTO from the given request.
     *
     * This method extracts validated data from the request, generates a slug
     * based on the name field, and returns a new instance of the CategoryData DTO.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing validated data.
     * @return self A new instance of the CategoryData DTO populated with the name and slug.
     */
    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        return new self(
            name: $data['name'],
            slug: $data['slug']
        );
    }
}

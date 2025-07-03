<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\ApiResponse;
use App\Enums\ApiAction;
use App\DTOs\PostData;
use App\Services\PostService;

use App\Http\Requests\Api\V1\StorePostRequest;
use App\Http\Requests\Api\V1\UpdatePostRequest;
use App\Http\Resources\V1\PostResource;

use App\Models\Post;

class PostController extends Controller
{
    use ApiResponse;

    public function __construct(protected PostService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = $this->service->list($request->get('per_page', 10)); // opsional perPage override

        return $this->successPaginatedResponse(
            PostResource::collection($posts),
            $this->generateMessage(Post::class, ApiAction::LIST)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $dto = PostData::fromRequest($request);
        $created = $this->service->store($dto);

        if (!$created) {
            return $this->errorResponse(
                $this->generateErrorMessage(Post::class, 'Failed to store post.'),
                null,
                500
            );
        }

        return $this->successResponse(
            new PostResource($created->load('categories')),
            $this->generateMessage(Post::class, ApiAction::CREATED),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $post = $this->service->find($id);
        if (!$post) {
            return $this->errorResponse(
                $this->generateErrorMessage(Post::class),
                null,
                404
            );
        }

        return $this->successResponse(
            new PostResource($post->load('categories')),
            $this->generateMessage($post, ApiAction::RETRIEVED)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $dto = PostData::fromRequestForUpdate($request);
        $updated = $this->service->update($post, $dto);

        if (!$updated) {
            return $this->errorResponse(
                $this->generateErrorMessage(Post::class, 'Failed to update post.'),
                null,
                500
            );
        }

        return $this->successResponse(
            new PostResource($post->load('categories')),
            $this->generateMessage($post, ApiAction::UPDATED)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $post = $this->service->find($id);

        if (!$post) {
            return $this->errorResponse(
                $this->generateErrorMessage(Post::class),
                null,
                404
            );
        }

        $this->service->delete($post);

        return $this->successResponse(
            null,
            $this->generateMessage($post, ApiAction::DELETED),
            204
        );
    }
}

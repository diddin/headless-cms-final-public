<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\ApiResponse;
use App\Enums\ApiAction;
use App\DTOs\CategoryData;
use App\Services\CategoryService;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryResource;

use App\Models\Category;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(protected CategoryService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $this->service->list($request->get('per_page', 10));

        return $this->successPaginatedResponse(
            CategoryResource::collection($categories),
            $this->generateMessage(Category::class, ApiAction::LIST)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $dto = CategoryData::fromRequest($request);
        $created = $this->service->store($dto);

        if (!$created) {
            return $this->errorResponse(
                $this->generateErrorMessage(Category::class, 'Failed to store category.'),
                null,
                500
            );
        }

        return $this->successResponse(
            new CategoryResource($created),
            $this->generateMessage(Category::class, ApiAction::CREATED),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $category = $this->service->find($id);

        if (!$category) {
            return $this->errorResponse(
                $this->generateErrorMessage(Category::class),
                null,
                404
            );
        }

        return $this->successResponse(
            new CategoryResource($category),
            $this->generateMessage($category, ApiAction::RETRIEVED)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $dto = CategoryData::fromRequest($request);
        $updated = $this->service->update($category, $dto);

        if (!$updated) {
            return $this->errorResponse(
                $this->generateErrorMessage(Category::class, 'Failed to update category.'),
                null,
                500
            );
        }

        return $this->successResponse(
            new CategoryResource($category),
            $this->generateMessage($category, ApiAction::UPDATED)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $category = $this->service->find($id);

        if (!$category) {
            return $this->errorResponse(
                $this->generateErrorMessage(Category::class),
                null,
                404
            );
        }

        $this->service->delete($category);

        return $this->successResponse(
            null,
            $this->generateMessage($category, ApiAction::DELETED),
            204
        );
    }
}

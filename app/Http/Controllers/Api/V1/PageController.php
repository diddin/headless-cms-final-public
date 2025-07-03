<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\ApiResponse;
use App\Enums\ApiAction;
use App\DTOs\PageData;
use App\Services\PageService;

use App\Http\Requests\Api\V1\StorePageRequest;
use App\Http\Requests\Api\V1\UpdatePageRequest;
use App\Http\Resources\V1\PageResource;

use App\Models\Page;

class PageController extends Controller
{
    use ApiResponse;

    public function __construct(protected PageService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pages = $this->service->list($request->get('per_page', 10));

        return $this->successPaginatedResponse(
            PageResource::collection($pages),
            $this->generateMessage(Page::class, ApiAction::LIST)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        $dto = PageData::fromRequest($request);
        $created = $this->service->store($dto);

        if (!$created) {
            return $this->errorResponse(
                $this->generateErrorMessage(Page::class, 'Failed to store page.'),
                null,
                500
            );
        }

        return $this->successResponse(
            new PageResource($created),
            $this->generateMessage(Page::class, ApiAction::CREATED),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $page = $this->service->find($id);
        
        if (!$page) {
            return $this->errorResponse(
                $this->generateErrorMessage(Page::class),
                null,
                404
            );
        }

        return $this->successResponse(
            new PageResource($page),
            $this->generateMessage($page, ApiAction::RETRIEVED)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $dto = PageData::fromRequestForUpdate($request);
        $updated = $this->service->update($page, $dto);
        
        if (!$updated) {
            return $this->errorResponse(
                $this->generateErrorMessage(Page::class, 'Failed to update page.'),
                null,
                500
            );
        }

        return $this->successResponse(
            new PageResource($page),
            $this->generateMessage($page, ApiAction::UPDATED)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $page = $this->service->find($id);
        if (!$page) {
            return $this->errorResponse(
                $this->generateErrorMessage(Page::class),
                null,
                404
            );
        }

        $this->service->delete($page);

        return $this->successResponse(
            null,
            $this->generateMessage($page, ApiAction::DELETED),
            204
        );
    }
}

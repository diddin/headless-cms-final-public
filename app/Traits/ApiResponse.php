<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ApiAction;

trait ApiResponse
{
    /**
     * Generate a successful JSON response.
     *
     * @param mixed $data The data to include in the response (optional).
     * @param string $message A message describing the response (optional).
     * @param int $status The HTTP status code for the response (default is 200).
     * @return \Illuminate\Http\JsonResponse The JSON response object.
     */
    protected function successResponse($data = null, string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Return paginated success response from a Laravel Resource Collection.
     */
    protected function successPaginatedResponse(JsonResource $resource, string $message = '', int $status = 200): JsonResponse
    {
        $response = $resource->response()->getData(true);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => [
                'items' => $response['data'],
                'meta'  => $response['meta'] ?? null,
                'links' => $response['links'] ?? null,
            ],
        ], $status);
    }

    /**
     * Generates a JSON error response.
     *
     * @param string $message The error message to be returned. Defaults to 'Something went wrong.'
     * @param mixed|null $errors Additional error details or data. Defaults to null.
     * @param int $status The HTTP status code for the response. Defaults to 400.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the error details.
     */
    protected function errorResponse(string $message = 'Something went wrong.', $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $errors,
        ], $status);
    }

    /**
     * Generate success message based on model and action
     */
    protected function generateMessage($model, ApiAction $action): string
    {
        $modelName = $this->formatModelName($model);
        return "{$modelName} has been successfully {$action->toMessageVerb()}.";
    }

    /**
     * Generate error message based on model and error type
     */
    protected function generateErrorMessage($model, string $type = 'not found'): string
    {
        $modelName = $this->formatModelName($model);
        return "{$modelName} {$type}.";
    }

    /**
     * Helper: Format class name into readable string (e.g., UserProfile → "User Profile")
     */
    protected function formatModelName($model): string
    {
        $modelName = is_object($model) ? class_basename($model) : class_basename($model);
        return preg_replace('/(?<!^)[A-Z]/', ' $0', $modelName);
    }
}

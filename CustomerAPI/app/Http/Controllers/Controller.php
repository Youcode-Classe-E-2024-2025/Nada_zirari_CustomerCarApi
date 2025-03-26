<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @OA\Info(
 *     title="Customer Care API",
 *     version="1.0.0",
 *     description="API for customer support ticket management"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    /**
     * Return a successful JSON response with data
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }
    
    /**
     * Return a paginated JSON response
     *
     * @param LengthAwarePaginator $paginator
     * @param string $message
     * @return JsonResponse
     */
    protected function paginatedResponse(LengthAwarePaginator $paginator, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => [
                'current_page' => $paginator->currentPage(),
                'data' => $paginator->items(),
                'first_page_url' => $paginator->url(1),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'last_page_url' => $paginator->url($paginator->lastPage()),
                'links' => $paginator->linkCollection()->toArray(),
                'next_page_url' => $paginator->nextPageUrl(),
                'path' => $paginator->path(),
                'per_page' => $paginator->perPage(),
                'prev_page_url' => $paginator->previousPageUrl(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total()
            ]
        ]);
    }
    
    /**
     * Return an error JSON response
     *
     * @param string $message
     * @param int $code
     * @param array|null $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code, array $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];
        
        if ($errors) {
            $response['errors'] = $errors;
        }
        
        return response()->json($response, $code);
    }
}

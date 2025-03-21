<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResponseController extends Controller
{
    protected $responseService;

    /**
     * Constructor to inject the ResponseService
     */
    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }


    public function index(): JsonResponse
    {
        $responses = $this->responseService->getAllResponses();
        return response()->json(['data' => $responses], 200);
    }


    public function show($id): JsonResponse
    {
        $response = $this->responseService->getResponseById($id);
        
        if (!$response) {
            return response()->json(['message' => 'Response not found'], 404);
        }
        
        return response()->json(['data' => $response], 200);
    }


    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'content' => 'required|string',
        ]);
        
        $response = $this->responseService->createResponse($validatedData);
        
        return response()->json(['message' => 'Response created successfully', 'data' => $response], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);
        
        $updated = $this->responseService->updateResponse($id, $validatedData);
        
        if (!$updated) {
            return response()->json(['message' => 'Response not found'], 404);
        }
        
        return response()->json(['message' => 'Response updated successfully', 'data' => $updated], 200);
    }


    public function destroy($id): JsonResponse
    {
        $deleted = $this->responseService->deleteResponse($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Response not found'], 404);
        }
        
        return response()->json(['message' => 'Response deleted successfully'], 200);
    }

}




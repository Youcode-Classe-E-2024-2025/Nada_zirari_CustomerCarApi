<?php

namespace App\Services;

use App\Repositories\ResponseRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResponseService
{
    protected $responseRepository;
    protected $ticketRepository;

    public function __construct(
        ResponseRepository $responseRepository,
        TicketRepository $ticketRepository
    ) {
        $this->responseRepository = $responseRepository;
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Get all responses
     *
     * @return array
     */
    public function getAllResponses(): array
    {
        return $this->responseRepository->getAll()->toArray();
    }

    /**
     * Get responses for a specific ticket
     *
     * @param int $ticketId
     * @return array
     * @throws ModelNotFoundException
     */
    public function getResponsesByTicketId(int $ticketId): array
    {
        // Check if ticket exists
        $ticket = $this->ticketRepository->findById($ticketId);
        if (!$ticket) {
            throw new ModelNotFoundException('Ticket not found');
        }
        
        return $this->responseRepository->getByTicketId($ticketId)->toArray();
    }

    /**
     * Get a response by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getResponseById(int $id): ?array
    {
        $response = $this->responseRepository->findById($id);
        
        if (!$response) {
            return null;
        }
        
        return $response->toArray();
    }

    /**
     * Create a new response
     *
     * @param array $data
     * @return array
     * @throws ModelNotFoundException
     */
    public function createResponse(array $data): array
    {
        // Check if ticket exists
        $ticket = $this->ticketRepository->findById($data['ticket_id']);
        if (!$ticket) {
            throw new ModelNotFoundException('Ticket not found');
        }
        
        // Add user_id to data
        $data['user_id'] = Auth::id();
        
        $response = $this->responseRepository->create($data);
        
        return $response->toArray();
    }

    /**
     * Update a response
     *
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updateResponse(int $id, array $data): ?array
    {
        $response = $this->responseRepository->findById($id);
        
        if (!$response) {
            return null;
        }
        
        // Check if user has permission to update this response
        if ($response->user_id !== Auth::id() && !(Auth::user()->isAdmin ?? false)) {
            throw new \Exception('You do not have permission to update this response', 403);
        }
        
        $updatedResponse = $this->responseRepository->update($id, $data);
        
        return $updatedResponse ? $updatedResponse->toArray() : null;
    }

    /**
     * Delete a response
     *
     * @param int $id
     * @return bool
     */
    public function deleteResponse(int $id): bool
    {
        $response = $this->responseRepository->findById($id);
        
        if (!$response) {
            return false;
        }
        
        // Check if user has permission to delete this response
        if ($response->user_id !== Auth::id() && !(Auth::user()->isAdmin ?? false)) {
            throw new \Exception('You do not have permission to delete this response', 403);
        }
        
        return $this->responseRepository->delete($id);
    }
}

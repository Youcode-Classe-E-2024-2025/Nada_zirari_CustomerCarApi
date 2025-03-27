<?php

namespace App\Repositories;

use App\Models\Response;
use Illuminate\Database\Eloquent\Collection;

class ResponseRepository
{
    protected $model;

    public function __construct(Response $model)
    {
        $this->model = $model;
    }

    /**
     * Get all responses
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get responses for a specific ticket
     *
     * @param int $ticketId
     * @return Collection
     */
    public function getByTicketId(int $ticketId): Collection
    {
        return $this->model->where('ticket_id', $ticketId)->get();
    }

    /**
     * Find a response by ID
     *
     * @param int $id
     * @return Response|null
     */
    public function findById(int $id): ?Response
    {
        return $this->model->find($id);
    }

    /**
     * Create a new response
     *
     * @param array $data
     * @return Response
     */
    public function create(array $data): Response
    {
        return $this->model->create($data);
    }

    /**
     * Update a response
     *
     * @param int $id
     * @param array $data
     * @return Response|null
     */
    public function update(int $id, array $data): ?Response
    {
        $response = $this->findById($id);
        
        if ($response) {
            $response->update($data);
            return $response->fresh();
        }
        
        return null;
    }

    /**
     * Delete a response
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $response = $this->findById($id);
        
        if ($response) {
            return $response->delete();
        }
        
        return false;
    }
}

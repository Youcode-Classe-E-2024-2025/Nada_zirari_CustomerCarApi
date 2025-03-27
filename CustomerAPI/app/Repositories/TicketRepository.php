<?php

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository
{
    protected $model;

    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }

    /**
     * Get all tickets with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllWithPagination(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('responses')->paginate($perPage);
    }

    /**
     * Get tickets for a specific user with pagination
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByUserWithPagination(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->with('responses')
            ->paginate($perPage);
    }

    /**
     * Find a ticket by ID
     *
     * @param int $id
     * @return Ticket|null
     */
    public function findById(int $id): ?Ticket
    {
        return $this->model->with('responses')->find($id);
    }

    /**
     * Create a new ticket
     *
     * @param array $data
     * @return Ticket
     */
    public function create(array $data): Ticket
    {
        return $this->model->create($data);
    }

    /**
     * Update a ticket
     *
     * @param int $id
     * @param array $data
     * @return Ticket|null
     */
    public function update(int $id, array $data): ?Ticket
    {
        $ticket = $this->findById($id);
        
        if ($ticket) {
            $ticket->update($data);
            return $ticket->fresh();
        }
        
        return null;
    }

    /**
     * Delete a ticket
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $ticket = $this->findById($id);
        
        if ($ticket) {
            return $ticket->delete();
        }
        
        return false;
    }
}

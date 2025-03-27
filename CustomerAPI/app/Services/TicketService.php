<?php

namespace App\Services;

use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketService
{
    protected $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Get all tickets
     *
     * @param int $perPage
     * @return array
     */
    public function getTickets(int $perPage = 10): array
    {
        $user = Auth::user();
        
        // If user is admin, get all tickets, otherwise get only user's tickets
        if ($user->isAdmin ?? false) {
            $tickets = $this->ticketRepository->getAllWithPagination($perPage);
        } else {
            $tickets = $this->ticketRepository->getByUserWithPagination($user->id, $perPage);
        }
        
        return [
            'success' => true,
            'data' => $tickets
        ];
    }

    /**
     * Get a ticket by ID
     *
     * @param int $id
     * @return array
     * @throws ModelNotFoundException
     */
    public function getTicketById(int $id): array
    {
        $ticket = $this->ticketRepository->findById($id);
        
        if (!$ticket) {
            throw new ModelNotFoundException('Ticket not found');
        }
        
        // Check if user has permission to view this ticket
        $user = Auth::user();
        if (!($user->isAdmin ?? false) && $ticket->user_id !== $user->id) {
            throw new \Exception('You do not have permission to view this ticket', 403);
        }
        
        return [
            'success' => true,
            'data' => $ticket
        ];
    }

    /**
     * Create a new ticket
     *
     * @param array $data
     * @return array
     */
    public function createTicket(array $data): array
    {
        // Set default values if not provided
        $data['status'] = $data['status'] ?? 'open';
        $data['priority'] = $data['priority'] ?? 'medium';
        
        $ticket = $this->ticketRepository->create($data);
        
        return [
            'success' => true,
            'message' => 'Ticket created successfully',
            'data' => $ticket
        ];
    }

    /**
     * Update a ticket
     *
     * @param int $id
     * @param array $data
     * @return array
     * @throws ModelNotFoundException
     */
    public function updateTicket(int $id, array $data): array
    {
        $ticket = $this->ticketRepository->findById($id);
        
        if (!$ticket) {
            throw new ModelNotFoundException('Ticket not found');
        }
        
        // Check if user has permission to update this ticket
        $user = Auth::user();
        if (!($user->isAdmin ?? false) && $ticket->user_id !== $user->id) {
            throw new \Exception('You do not have permission to update this ticket', 403);
        }
        
        $updatedTicket = $this->ticketRepository->update($id, $data);
        
        return [
            'success' => true,
            'message' => 'Ticket updated successfully',
            'data' => $updatedTicket
        ];
    }

    /**
     * Delete a ticket
     *
     * @param int $id
     * @return array
     * @throws ModelNotFoundException
     */
    public function deleteTicket(int $id): array
    {
        $ticket = $this->ticketRepository->findById($id);
        
        if (!$ticket) {
            throw new ModelNotFoundException('Ticket not found');
        }
        
        // Check if user has permission to delete this ticket
        $user = Auth::user();
        if (!($user->isAdmin ?? false) && $ticket->user_id !== $user->id) {
            throw new \Exception('You do not have permission to delete this ticket', 403);
        }
        
        $this->ticketRepository->delete($id);
        
        return [
            'success' => true,
            'message' => 'Ticket deleted successfully'
        ];
    }
}

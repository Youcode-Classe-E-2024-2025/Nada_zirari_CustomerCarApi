<?php
namespace App\Services;

use App\Models\Ticket;

class TicketService {
    public function createTicket(array $data) {
        return Ticket::create($data);
    }

    public function getTickets() {
        return Ticket::all();
    }

    public function getTicketById($id) {
        return Ticket::findOrFail($id);
    }

    public function updateTicket($id, array $data) {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);
        return $ticket;
    }

    public function deleteTicket($id) {
        Ticket::destroy($id);
    }
}
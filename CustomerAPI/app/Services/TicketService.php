<?php
namespace App\Services;

use App\Models\Response;

class ResponseService {
    public function addResponse(array $data) {
        return Response::create($data);
    }

    public function getResponsesForTicket($ticketId) {
        return Response::where('ticket_id', $ticketId)->get();
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
   
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }
    public function index()
    {
        return response()->json($this->ticketService->getTickets(), 200);
    }


    public function store(Request $request, TicketService $ticketService)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $ticket = $ticketService->createTicket([
        'user_id' => auth()->id(),
        'title' => $validated['title'],
        'description' => $validated['description'],
    ]);

    return response()->json($ticket, 201);
}

public function show($id)
    {
        return response()->json($this->ticketService->getTicketById($id), 200);
    }


public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'status' => 'sometimes|in:open,in_progress,closed',
        'assigned_to' => 'nullable|exists:users,id',
    ]);

    return response()->json($this->ticketService->updateTicket($id, $request->all()), 200);
}

public function destroy($id)
    {
        $this->ticketService->deleteTicket($id);
        return response()->json(['message' => 'Ticket supprimé avec succès'], 200);
    }
}

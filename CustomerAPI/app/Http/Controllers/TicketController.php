<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
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
}

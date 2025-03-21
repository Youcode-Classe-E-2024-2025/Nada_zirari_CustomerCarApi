<?php
namespace App\Services;

use App\Models\Response;
use Illuminate\Support\Facades\Auth;

class ResponseService {



    public function getAllResponses()
    {
        return Response::with('ticket')->get();
    }

public function getResponseById($id)
    {
        return Response::with('ticket')->find($id);
    }


    public function createResponse(array $data)
    {
        $data['user_id'] = Auth::id();
        return Response::create($data);
    }

public function updateResponse($id, array $data)
    {
        $response = Response::find($id);
        
        if (!$response) {
            return null;
        }
        
        $response->update($data);
        return $response;
    }

    public function deleteResponse($id)
    {
        $response = Response::find($id);
        
        if (!$response) {
            return false;
        }
        
        return $response->delete();
    }
    // public function addResponse(array $data) {
    //     return Response::create($data);
    // }

    // public function getResponsesForTicket($ticketId) {
    //     return Response::where('ticket_id', $ticketId)->get();
    // }
}
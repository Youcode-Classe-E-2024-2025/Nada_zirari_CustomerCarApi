<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('tickets.responses', ResponseController::class);

    Route::get('tickets/{ticketId}/responses', [ResponseController::class, 'index']);
    Route::post('tickets/{ticketId}/responses', [ResponseController::class, 'store']);

    Route::apiResource('responses', ResponseController::class);

    Route::put('responses/{id}', [ResponseController::class, 'update']);
    Route::delete('responses/{id}', [ResponseController::class, 'destroy']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

});

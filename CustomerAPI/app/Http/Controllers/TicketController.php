<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
class TicketController extends Controller
{
   
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

 /**
     * @OA\Get(
     *     path="/api/tickets",
     *     summary="Récupérer la liste des tickets",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des tickets récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Problème de connexion"),
     *                         @OA\Property(property="description", type="string", example="Je n'arrive pas à me connecter à mon compte"),
     *                         @OA\Property(property="status", type="string", example="open"),
     *                         @OA\Property(property="priority", type="string", example="high"),
     *                         @OA\Property(property="user_id", type="integer", example=1),
     *                         @OA\Property(property="category_id", type="integer", example=2),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=50)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index()
    {
        return response()->json($this->ticketService->getTickets(), 200);
    }

  /**
     * @OA\Post(
     *     path="/api/tickets",
     *     summary="Créer un nouveau ticket",
     *     tags={"Tickets"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "priority", "category_id"},
     *             @OA\Property(property="title", type="string", example="Problème de connexion"),
     *             @OA\Property(property="description", type="string", example="Je n'arrive pas à me connecter à mon compte"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="high"),
     *             @OA\Property(property="category_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ticket créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ticket created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Problème de connexion"),
     *                 @OA\Property(property="description", type="string", example="Je n'arrive pas à me connecter à mon compte"),
     *                 @OA\Property(property="status", type="string", example="open"),
     *                 @OA\Property(property="priority", type="string", example="high"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="category_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"title": {"Le champ titre est obligatoire"}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/tickets/{id}",
     *     summary="Récupérer les détails d'un ticket",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du ticket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du ticket récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Problème de connexion"),
     *                 @OA\Property(property="description", type="string", example="Je n'arrive pas à me connecter à mon compte"),
     *                 @OA\Property(property="status", type="string", example="open"),
     *                 @OA\Property(property="priority", type="string", example="high"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="category_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="responses",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="content", type="string", example="Veuillez essayer de réinitialiser votre mot de passe"),
     *                         @OA\Property(property="user_id", type="integer", example=2),
     *                         @OA\Property(property="ticket_id", type="integer", example=1),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ticket not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
public function show($id)
    {
        return response()->json($this->ticketService->getTicketById($id), 200);
    }

/**
     * @OA\Put(
     *     path="/api/tickets/{id}",
     *     summary="Mettre à jour un ticket",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du ticket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Problème de connexion - Mise à jour"),
     *             @OA\Property(property="description", type="string", example="Description mise à jour"),
     *             @OA\Property(property="status", type="string", enum={"open", "in_progress", "closed"}, example="in_progress"),
     *             @OA\Property(property="assigned_to", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Problème de connexion - Mise à jour"),
     *             @OA\Property(property="description", type="string", example="Description mise à jour"),
     *             @OA\Property(property="status", type="string", example="in_progress"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="assigned_to", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"status": {"Le statut doit être l'une des valeurs suivantes: open, in_progress, closed."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
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

 /**
     * @OA\Delete(
     *     path="/api/tickets/{id}",
     *     summary="Supprimer un ticket",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du ticket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *  *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
public function destroy($id)
    {
        $this->ticketService->deleteTicket($id);
        return response()->json(['message' => 'Ticket supprimé avec succès'], 200);
    }
}

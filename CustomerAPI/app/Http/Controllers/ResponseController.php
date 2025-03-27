<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResponseRequest;
use App\Http\Requests\UpdateResponseRequest;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Responses",
 *     description="API Endpoints pour la gestion des réponses aux tickets"
 * )
 */
class ResponseController extends Controller
{
    protected $responseService;

    /**
     * Constructor to inject the ResponseService
     */
    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    /**
     * @OA\Get(
     *     path="/api/responses",
     *     summary="Récupérer la liste des réponses",
     *     tags={"Responses"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des réponses récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="content", type="string", example="Veuillez essayer de réinitialiser votre mot de passe"),
     *                     @OA\Property(property="user_id", type="integer", example=2),
     *                     @OA\Property(property="ticket_id", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
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
    public function index(): JsonResponse
    {
        $responses = $this->responseService->getAllResponses();
        return response()->json(['data' => $responses], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/responses/{id}",
     *     summary="Récupérer les détails d'une réponse",
     *     tags={"Responses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la réponse",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la réponse récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="Veuillez essayer de réinitialiser votre mot de passe"),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="ticket_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Réponse non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Response not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show($id): JsonResponse
    {
        $response = $this->responseService->getResponseById($id);
        
        if (!$response) {
            return response()->json(['message' => 'Response not found'], 404);
        }
        
        return response()->json(['data' => $response], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/responses",
     *     summary="Créer une nouvelle réponse",
     *     tags={"Responses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ticket_id", "content"},
     *             @OA\Property(property="ticket_id", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="Veuillez essayer de réinitialiser votre mot de passe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Réponse créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Response created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="Veuillez essayer de réinitialiser votre mot de passe"),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="ticket_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
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
     *                 example={"ticket_id": {"Le ticket n'existe pas."}, "content": {"Le contenu est obligatoire."}}
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
    public function store(StoreResponseRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $response = $this->responseService->createResponse($validatedData);
            
            return response()->json([
                'message' => 'Response created successfully', 
                'data' => $response
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    /**
     
     * @OA\Put(
     *     path="/api/responses/{id}",
     *     summary="Mettre à jour une réponse",
     *     tags={"Responses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la réponse",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Contenu mis à jour de la réponse")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Réponse mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Response updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="Contenu mis à jour de la réponse"),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="ticket_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Réponse non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Response not found")
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
     *                 example={"content": {"Le contenu est obligatoire."}}
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
    public function update(UpdateResponseRequest $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updated = $this->responseService->updateResponse($id, $validatedData);
            
            if (!$updated) {
                return response()->json(['message' => 'Response not found'], 404);
            }
            
            return response()->json([
                'message' => 'Response updated successfully', 
                'data' => $updated
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/responses/{id}",
     *     summary="Supprimer une réponse",
     *     tags={"Responses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la réponse",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Réponse supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Response deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Réponse non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Response not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $deleted = $this->responseService->deleteResponse($id);
            
            if (!$deleted) {
                return response()->json(['message' => 'Response not found'], 404);
            }
            
            return response()->json(['message' => 'Response deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}

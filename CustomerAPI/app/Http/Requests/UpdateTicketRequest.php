<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Vous pouvez ajouter une logique d'autorisation plus complexe si nécessaire
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|string|in:open,in_progress,closed',
            'priority' => 'sometimes|string|in:low,medium,high',
            'category_id' => 'sometimes|exists:categories,id',
            'assigned_to' => 'sometimes|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'status.in' => 'Le statut doit être l\'une des valeurs suivantes: open, in_progress, closed.',
            'priority.in' => 'La priorité doit être l\'une des valeurs suivantes: low, medium, high.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'assigned_to.exists' => 'L\'utilisateur assigné n\'existe pas.',
        ];
    }
}

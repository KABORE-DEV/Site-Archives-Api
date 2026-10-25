<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArchiveRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // L'utilisateur doit être connecté et être admin ou chef de filière
        $user = $this->user();
        return $user && ($user->isAdmin() || $user->isChefFiliere());
    }

    /**
     * Règles de validation pour l'upload d'une archive.
     */
    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:devoir,test,examen,autre',
            'module_id' => 'required|exists:modules,id',
            'annee_academique_id' => 'required|exists:annee_academiques,id',
            'file' => 'required|file|mimes:pdf,doc,docx,odt,jpg,jpeg,png|max:10240', // Max 10 Mo
        ];
    }

    /**
     * Messages personnalisés de validation.
     */
    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre du document est obligatoire.',
            'type.required' => 'Le type de document (devoir, test, examen ou autre) est obligatoire.',
            'type.in' => 'Le type doit être : devoir, test, examen ou autre.',
            'module_id.required' => 'Le module associé est obligatoire.',
            'module_id.exists' => 'Le module sélectionné est introuvable.',
            'annee_academique_id.required' => 'L\'année académique est obligatoire.',
            'annee_academique_id.exists' => 'L\'année académique sélectionnée est introuvable.',
            'file.required' => 'Veuillez joindre le fichier de l\'archive.',
            'file.mimes' => 'Le format du fichier doit être un PDF, DOC, DOCX, ODT ou une image (JPG, PNG).',
            'file.max' => 'La taille maximale autorisée pour le document est de 10 Mo.',
        ];
    }
}

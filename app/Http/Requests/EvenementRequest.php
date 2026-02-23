<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvenementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date' => ['required', 'date'],
            'heure' => ['required', 'date_format:H:i'],
            'lieu' => ['required', 'string', 'max:255'],
            'num_rue' => ['nullable', 'string', 'max:50'],
            'allee' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'pays' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'diffusion_id' => ['required', 'integer', 'in:1,2,3'],
            'categorie' => ['nullable', 'array'],
            'categorie.*' => ['integer', 'exists:categorie,id'],
            'annonciateur' => ['nullable', 'boolean'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l’événement est obligatoire.',
            'date.required' => 'La date est obligatoire.',
            'heure.required' => 'L’heure est obligatoire.',
            'lieu.required' => 'Le lieu est obligatoire.',
            'diffusion_id.required' => 'La visibilité est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'categorie.*.exists' => 'Catégorie invalide.',
        ];
    }
}

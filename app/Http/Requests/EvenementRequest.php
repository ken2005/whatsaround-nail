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
        ];
    }
}

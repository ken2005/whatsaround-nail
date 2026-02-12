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
}

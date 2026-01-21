<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RenovacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha_renovacion' => 'required|date',
            'costo_ciclo' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_renovacion.required' => 'La fecha de renovación es obligatoria.',
            'fecha_renovacion.date' => 'La fecha de renovación debe ser válida.',
            'costo_ciclo.required' => 'El costo del ciclo es obligatorio.',
            'costo_ciclo.numeric' => 'El costo debe ser un número.',
            'costo_ciclo.min' => 'El costo no puede ser negativo.',
        ];
    }
}

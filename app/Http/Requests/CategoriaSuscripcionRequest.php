<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriaSuscripcionRequest extends FormRequest
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
        // Obtener el ID de la categoría si estamos editando
        $categoriaId = $this->route('categorias_suscripcion');
        
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categorias_suscripcion', 'nombre')->ignore($categoriaId),
            ],
            'estatus' => 'required|in:activo,inactivo',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres.',
            'nombre.unique' => 'Ya existe una categoría de suscripción con este nombre.',
            'estatus.required' => 'El estatus es obligatorio.',
            'estatus.in' => 'El estatus debe ser activo o inactivo.',
        ];
    }
}

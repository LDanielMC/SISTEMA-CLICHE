<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuscripcionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convertir dias_recordatorio de string "15,7,3" a array [15,7,3]
        if ($this->has('dias_recordatorio') && is_string($this->dias_recordatorio)) {
            $this->merge([
                'dias_recordatorio' => array_map(
                    'intval',
                    array_filter(
                        explode(',', $this->dias_recordatorio),
                        fn($val) => trim($val) !== ''
                    )
                )
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'idCategoria' => 'required|exists:categorias_suscripcion,idCategoria',
            'nombre_servicio' => 'required|string|max:150',
            'fecha_inicio' => 'required|date',
            'costo' => 'required|numeric|min:0',
            'periodicidad' => 'required|in:mensual,anual',
            'dias_recordatorio' => 'nullable|array',
            'dias_recordatorio.*' => 'integer|min:1|max:365',
            'nivel_uso' => 'required|in:bajo,medio,alto',
            'observaciones' => 'nullable|string',
            'estatus' => 'required|in:activo,inactivo',
        ];
    }

    public function messages(): array
    {
        return [
            'idCategoria.required' => 'Debe seleccionar una categoría.',
            'idCategoria.exists' => 'La categoría seleccionada no existe.',
            'nombre_servicio.required' => 'El nombre del servicio es obligatorio.',
            'nombre_servicio.max' => 'El nombre no puede exceder 150 caracteres.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser válida.',
            'costo.required' => 'El costo es obligatorio.',
            'costo.numeric' => 'El costo debe ser un número.',
            'costo.min' => 'El costo no puede ser negativo.',
            'periodicidad.required' => 'La periodicidad es obligatoria.',
            'periodicidad.in' => 'La periodicidad debe ser mensual o anual.',
            'dias_recordatorio.array' => 'Los días de recordatorio deben ser un array.',
            'dias_recordatorio.*.integer' => 'Cada día de recordatorio debe ser un número entero.',
            'dias_recordatorio.*.min' => 'Los días de recordatorio deben ser al menos 1.',
            'dias_recordatorio.*.max' => 'Los días de recordatorio no pueden exceder 365.',
            'nivel_uso.required' => 'El nivel de uso es obligatorio.',
            'nivel_uso.in' => 'El nivel de uso debe ser bajo, medio o alto.',
            'estatus.required' => 'El estatus es obligatorio.',
            'estatus.in' => 'El estatus debe ser activo o inactivo.',
        ];
    }
}

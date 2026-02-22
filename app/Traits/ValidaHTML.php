<?php

namespace App\Traits;

trait ValidaHTML
{
    /**
     * Limpia el contenido HTML de editores de texto enriquecido (como Quill)
     * y detecta si está realmente vacío.
     * 
     * Convierte HTML vacío (como <p><br></p>, <p></p>, etc.) a null 
     * para que la validación 'required' de Laravel funcione correctamente.
     * 
     * @param string|null $html Contenido HTML a validar
     * @return string|null HTML original si tiene contenido, null si está vacío
     * 
     * Ejemplos:
     * - "<p><br></p>" → null (validación fallará)
     * - "<p>Texto real</p>" → "<p>Texto real</p>" (validación pasará)
     * - "" → null (validación fallará)
     * - null → null (validación fallará)
     */
    protected function limpiarHTML($html)
    {
        if (empty($html)) {
            return null;
        }

        // Remover todas las etiquetas HTML y espacios en blanco
        $textoLimpio = strip_tags($html);
        $textoLimpio = trim($textoLimpio);

        // Si después de limpiar no hay contenido real, retornar null
        if (empty($textoLimpio)) {
            return null;
        }

        // Si hay contenido real, retornar el HTML original
        return $html;
    }

    /**
     * Limpia múltiples campos HTML a la vez.
     * Útil cuando tienes varios editores en un formulario.
     * 
     * @param array $campos Array con los nombres de campos a limpiar
     * @param \Illuminate\Http\Request $request Objeto Request
     * @return void
     * 
     * Uso:
     * $this->limpiarCamposHTML(['descripcion', 'observaciones'], $request);
     */
    protected function limpiarCamposHTML(array $campos, $request)
    {
        $datosLimpios = [];
        
        foreach ($campos as $campo) {
            $datosLimpios[$campo] = $this->limpiarHTML($request->input($campo));
        }
        
        $request->merge($datosLimpios);
    }
}

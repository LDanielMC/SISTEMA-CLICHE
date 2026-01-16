<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Models\Cliente;
use App\Models\Plataforma;
use App\Models\Formato;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PublicacionController extends Controller
{
    public function index()
    {
        $eventos = Publicacion::with(['cliente', 'plataforma', 'formato'])->get();

        $eventosFormateados = $eventos->map(function ($evento) {
            return [
                'id' => $evento->idPublicacion,
                'title' => ($evento->cliente?->nombre ?? 'Cliente') . ' - ' . ($evento->plataforma?->nombre ?? 'Plataforma'),
                'start' => $evento->fecha->format('Y-m-d'),
                'color' => $this->getColorPorEstatus($evento->estatus),
                'extendedProps' => [
                    'estatus' => $evento->estatus,
                    'formato' => $evento->formato?->nombre ?? ''
                ]
            ];
        });

        return view('calendario.general', compact('eventosFormateados'));
    }

    /**
     * /calendario/gestion?cliente_id=7
     * /calendario/gestion/7
     */
    public function gestionCliente(Request $request, $cliente_id = null)
    {
        $clientesActivos = Cliente::where('estatus', 'activo')
            ->orderBy('nombre', 'asc')
            ->get();

        $clienteIdFinal = $cliente_id ?: $request->query('cliente_id');

        $clienteSeleccionado = null;
        $calendariosPorAnio = collect();
        $plataformas = [];
        $formatos = [];

        if (!empty($clienteIdFinal)) {

            $clienteSeleccionado = Cliente::where('id_cliente', $clienteIdFinal)->first();

            if ($clienteSeleccionado) {

                $todasPublicaciones = Publicacion::where('cliente_id', $clienteIdFinal)
                    ->with(['plataforma', 'formato'])
                    ->orderBy('fecha', 'asc')
                    ->get();

                $grupos = $todasPublicaciones->groupBy(function ($p) {
                    return $p->lote_calendario ?: 'SIN_LOTE';
                });

                $tarjetas = $grupos->map(function ($grupo, $lote) {

                    if ($lote === 'SIN_LOTE') {
                        // Para que se vaya hasta abajo, lo mandamos a un año viejo
                        $inicio = Carbon::create(1900, 1, 1)->locale('es');
                        $fin = Carbon::create(1900, 1, 1)->locale('es');

                        return [
                            'lote' => $lote,
                            'titulo' => 'Publicaciones (sin calendario guardado)',
                            'inicio' => $inicio,
                            'fin' => $fin,
                            'anio' => 1900,
                            'total' => $grupo->count(),
                            'updated_at' => Carbon::parse($grupo->max('updated_at')),
                            'publicaciones' => $grupo->sortBy('fecha')->values(),
                        ];
                    }

                    $inicio = Carbon::parse($grupo->min('fecha'))->locale('es');
                    $fin = Carbon::parse($grupo->max('fecha'))->locale('es');

                    if ($inicio->year === $fin->year) {
                        $titulo = $inicio->isoFormat('D [de] MMMM') . ' - ' . $fin->isoFormat('D [de] MMMM') . ' ' . $inicio->year;
                    } else {
                        $titulo = $inicio->isoFormat('D [de] MMMM YYYY') . ' - ' . $fin->isoFormat('D [de] MMMM YYYY');
                    }

                    return [
                        'lote' => $lote,
                        'titulo' => $titulo,
                        'inicio' => $inicio->copy(),
                        'fin' => $fin->copy(),
                        'anio' => $inicio->year,
                        'total' => $grupo->count(),
                        'updated_at' => Carbon::parse($grupo->max('updated_at')),
                        'publicaciones' => $grupo->sortBy('fecha')->values(),
                    ];
                });

                // ✅ Orden por mes de inicio (ASC) y luego agrupamos por año
                $tarjetasOrdenadas = $tarjetas->sortBy(function ($t) {
                    return $t['inicio']->format('Y-m-d');
                })->values();

                // ✅ Agrupar por año y ordenar años DESC (más nuevo arriba)
                $calendariosPorAnio = $tarjetasOrdenadas->groupBy('anio')->sortKeysDesc();

                $plataformas = Plataforma::where('activo', true)->get();
                $formatos = Formato::where('activo', true)->get();
            }
        }

        return view('calendario.cliente_tabla', compact(
            'clientesActivos',
            'clienteSeleccionado',
            'calendariosPorAnio',
            'plataformas',
            'formatos'
        ));
    }

    public function storeMasivo(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id_cliente',
            'items' => 'required|array|min:1',
            'items.*.plataforma_id' => 'required|exists:plataformas,id',
            'items.*.formato_id' => 'required|exists:formatos,id',
            'items.*.fecha' => 'required|date',
            'items.*.estatus' => 'required|in:Pendiente,Publicado,Reprogramar',
        ]);

        $cliente_id = $request->cliente_id;
        $lote = (string) Str::uuid();

        $contador = 0;

        foreach ($request->items as $item) {
            if (!empty($item['fecha'])) {
                Publicacion::create([
                    'cliente_id' => $cliente_id,
                    'lote_calendario' => $lote,
                    'plataforma_id' => $item['plataforma_id'],
                    'formato_id' => $item['formato_id'],
                    'fecha' => $item['fecha'],
                    'copy' => $item['copy'] ?? null,
                    'arte' => $item['arte'] ?? null,
                    'estatus' => $item['estatus'],
                ]);
                $contador++;
            }
        }

        // ✅ Regresa al mismo cliente (con query param)
        return redirect()->route('calendario.gestion', ['cliente_id' => $cliente_id])
            ->with('success', "¡Listo! Se creó el calendario y se guardaron $contador publicaciones.");
    }

    /**
     * ✅ Actualización Masiva (Edición y Creación)
     */
    public function updateMasivo(Request $request)
    {
        // 1. Validar
        $data = $request->validate([
            // Validaciones para las publicaciones existentes
            'publicaciones' => 'nullable|array',
            'publicaciones.*.fecha' => 'required_with:publicaciones|date',
            'publicaciones.*.plataforma_id' => 'required_with:publicaciones|exists:plataformas,id',
            'publicaciones.*.formato_id' => 'required_with:publicaciones|exists:formatos,id',
            'publicaciones.*.estatus' => 'required_with:publicaciones|in:Pendiente,Publicado,Reprogramar',
            'publicaciones.*.copy' => 'nullable|string',
            'publicaciones.*.arte' => 'nullable|string',

            // Validaciones para las nuevas filas agregadas en el modal
            'nuevas' => 'nullable|array',
            'nuevas.*.fecha' => 'required_with:nuevas|date',
            'nuevas.*.plataforma_id' => 'required_with:nuevas|exists:plataformas,id',
            'nuevas.*.formato_id' => 'required_with:nuevas|exists:formatos,id',
            'nuevas.*.estatus' => 'required_with:nuevas|in:Pendiente,Publicado,Reprogramar',
            'nuevas.*.copy' => 'nullable|string',
            'nuevas.*.arte' => 'nullable|string',

            // Datos generales (necesarios para crear nuevas)
            'cliente_id' => 'required_with:nuevas|exists:clientes,id_cliente',
            'lote_calendario' => 'nullable|string',
        ]);

        $contador = 0;

        // 2. Iterar y actualizar las existentes
        if (!empty($data['publicaciones'])) {
            foreach ($data['publicaciones'] as $id => $campos) {
                $publicacion = Publicacion::find($id);

                if ($publicacion) {
                    $publicacion->update([
                        'fecha' => $campos['fecha'],
                        'plataforma_id' => $campos['plataforma_id'],
                        'formato_id' => $campos['formato_id'],
                        'copy' => $campos['copy'],
                        'arte' => $campos['arte'],
                        'estatus' => $campos['estatus'],
                    ]);
                    $contador++;
                }
            }
        }

        // 3. Crear las nuevas (si existen)
        if (!empty($data['nuevas'])) {
            $lote = ($request->lote_calendario === 'SIN_LOTE') ? null : $request->lote_calendario;

            foreach ($data['nuevas'] as $campos) {
                Publicacion::create([
                    'cliente_id' => $request->cliente_id,
                    'lote_calendario' => $lote,
                    'plataforma_id' => $campos['plataforma_id'],
                    'formato_id' => $campos['formato_id'],
                    'fecha' => $campos['fecha'],
                    'copy' => $campos['copy'] ?? null,
                    'arte' => $campos['arte'] ?? null,
                    'estatus' => $campos['estatus'],
                ]);
                $contador++;
            }
        }

        // 4. Retornar
        return back()->with('success', "Se procesaron $contador registros (actualizaciones y nuevos).");
    }

    public function update(Request $request, $idPublicacion)
    {
        $publicacion = Publicacion::findOrFail($idPublicacion);

        $request->validate([
            'estatus' => 'required|in:Pendiente,Publicado,Reprogramar',
        ]);

        $publicacion->update([
            'estatus' => $request->estatus,
        ]);

        return back()->with('success', 'Estatus actualizado.');
    }

    public function destroy($idPublicacion)
    {
        $publicacion = Publicacion::findOrFail($idPublicacion);
        $publicacion->delete();

        return back()->with('success', 'Publicación eliminada.');
    }

        //ELIMINAR LOTE COMPLETO (Acción desde la tarjeta del calendario)
    public function destroyLote(Request $request)
    {
        $request->validate([
            'lote_calendario' => 'required|string',
            'cliente_id' => 'required|exists:clientes,id_cliente'
        ]);

        // Borra todas las publicaciones que tengan ese ID de lote
        $deleted = Publicacion::where('lote_calendario', $request->lote_calendario)->delete();

        // Redirige al mismo dashboard del cliente
        return redirect()->route('calendario.gestion', ['cliente_id' => $request->cliente_id])
            ->with('success', "Calendario eliminado correctamente ($deleted publicaciones borradas).");
    }

    private function getColorPorEstatus($estatus)
    {
        return match ($estatus) {
            'Publicado' => '#28a745',
            'Pendiente' => '#ffc107',
            'Reprogramar' => '#dc3545',
            default => '#6c757d',
        };
    }
}
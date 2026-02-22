<?php

namespace App\Http\Controllers;

use App\Models\Minuta;
use App\Models\Acuerdo;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ValidaHTML;

class MinutaController extends Controller
{
    use ValidaHTML;
    public function index(Request $request)
    {
        $estatusFilter = $request->query('estatus', 'todas');
        $sortBy = $request->query('sort_by', 'id_minuta');
        $sortDir = $request->query('sort_dir', 'desc');

        $query = Minuta::with('cliente');

        if ($estatusFilter === 'completado') {
            // Para completadas: Que tenga acuerdos Y que NO tenga pendientes
            $query->whereHas('acuerdos')
                  ->whereDoesntHave('acuerdos', function($q) {
                      $q->where('estatus', 'pendiente');
                  });
        } elseif ($estatusFilter !== 'todas') {
            // Para pendientes: Que tenga al menos un acuerdo con ese estatus
            $query->whereHas('acuerdos', function($q) use ($estatusFilter) {
                $q->where('estatus', $estatusFilter);
            });
        }

        $minutas = $query->orderBy($sortBy, $sortDir)->paginate(10);

        return view('minutas.index', compact('minutas', 'estatusFilter', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        $clientes = Cliente::where('estatus', 'activo')->orderBy('nombre')->get();
        $nextId = Minuta::max('id_minuta') + 1;

        return view('minutas.create', compact('clientes', 'nextId'));
    }

    public function store(Request $request)
    {
        // Limpiar campos HTML antes de validar
        $request->merge([
            'asistentes' => $this->limpiarHTML($request->asistentes),
            'puntos_tratados' => $this->limpiarHTML($request->puntos_tratados),
            'observaciones' => $this->limpiarHTML($request->observaciones),
        ]);

        // Validar todos los campos con mensajes claros
        $validated = $request->validate([
            'id_cliente'        => 'required|integer|exists:clientes,id_cliente',
            'titulo'            => 'required|string|max:255',
            'fecha'             => 'required|date',
            'asistentes'        => 'required|string|max:500',
            'puntos_tratados'   => 'required|string',
            'observaciones'     => 'nullable|string',

            'acuerdos'                   => 'required|array|min:1',
            'acuerdos.*.acuerdo'         => 'required|string|max:1000',
            'acuerdos.*.responsable'     => 'nullable|string|max:255',
            'acuerdos.*.estatus'         => 'required|in:pendiente,completado',
            'acuerdos.*.fecha_limite'    => 'nullable|date',
        ], [
            'id_cliente.required' => 'El cliente es obligatorio.',
            'id_cliente.exists' => 'El cliente seleccionado no existe.',
            'titulo.required' => 'El título de la minuta es obligatorio.',
            'titulo.max' => 'El título no puede exceder 255 caracteres.',
            'fecha.required' => 'La fecha de la minuta es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'asistentes.required' => 'El campo "Asistentes" es obligatorio.',
            'asistentes.max' => 'El campo "Asistentes" no puede exceder 500 caracteres.',
            'puntos_tratados.required' => 'El campo "Puntos tratados" es obligatorio.',
            'acuerdos.required' => 'Debes agregar al menos un acuerdo.',
            'acuerdos.min' => 'Debes agregar al menos un acuerdo.',
            'acuerdos.*.acuerdo.required' => 'El texto del acuerdo es obligatorio en cada fila.',
            'acuerdos.*.acuerdo.max' => 'El texto del acuerdo no puede exceder 1000 caracteres.',
            'acuerdos.*.estatus.required' => 'El estatus es obligatorio en cada acuerdo.',
            'acuerdos.*.estatus.in' => 'El estatus debe ser "pendiente" o "completado".',
            'acuerdos.*.fecha_limite.date' => 'La fecha límite del acuerdo no es válida.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Crear minuta
                $minuta = Minuta::create([
                    'id_cliente'        => $validated['id_cliente'],
                    'titulo'            => $validated['titulo'] ?? null,
                    'fecha'             => $validated['fecha'],
                    'asistentes'        => $validated['asistentes'] ?? null,
                    'puntos_tratados'   => $validated['puntos_tratados'] ?? null,
                    'observaciones'     => $validated['observaciones'] ?? null,
                ]);

                // Crear acuerdos con orden
                // array_values() resetea los índices para guardar orden secuencial (0, 1, 2...)
                $acuerdosOrdenados = array_values($validated['acuerdos']);
                
                foreach ($acuerdosOrdenados as $index => $item) {
                    $minuta->acuerdos()->create([
                        'acuerdo'       => $item['acuerdo'],
                        'responsable'   => $item['responsable'] ?? null,
                        'orden'         => $index,
                        'estatus'       => $item['estatus'],
                        'fecha_limite'  => $item['fecha_limite'] ?? null,
                    ]);
                }
            });

            return redirect()->route('minutas.index')
                ->with('success', 'Minuta creada correctamente.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al guardar la minuta: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Minuta $minuta)
    {
        $clientes = Cliente::where('estatus', 'activo')->orderBy('nombre')->get();
        
        // Cargar los acuerdos ordenados por la columna 'orden'
        $minuta->load(['acuerdos' => function($query) {
            $query->orderBy('orden', 'asc');
        }]);
        
        return view('minutas.edit', compact('minuta', 'clientes'));
    }

    public function update(Request $request, Minuta $minuta)
    {
        // Limpiar campos HTML antes de validar
        $request->merge([
            'asistentes' => $this->limpiarHTML($request->asistentes),
            'puntos_tratados' => $this->limpiarHTML($request->puntos_tratados),
            'observaciones' => $this->limpiarHTML($request->observaciones),
        ]);

        $request->validate([
            'id_cliente'        => 'required|exists:clientes,id_cliente',
            'titulo'            => 'required|string|max:255',
            'fecha'             => 'required|date',
            'asistentes'        => 'required|string|max:500',
            'puntos_tratados'   => 'required|string|max:5000',
            'observaciones'     => 'nullable|string|max:5000',

            'acuerdos'                   => 'required|array|min:1',
            'acuerdos.*.id_acuerdo'      => 'nullable|integer',
            'acuerdos.*.acuerdo'         => 'required|string|max:1000',
            'acuerdos.*.responsable'     => 'nullable|string|max:255',
            'acuerdos.*.estatus'         => 'required|in:pendiente,completado',
            'acuerdos.*.fecha_limite'    => 'nullable|date',
        ], [
            'id_cliente.required' => 'El cliente es obligatorio.',
            'id_cliente.exists' => 'El cliente seleccionado no existe.',
            'titulo.required' => 'El título de la minuta es obligatorio.',
            'titulo.max' => 'El título no puede exceder 255 caracteres.',
            'fecha.required' => 'La fecha de la minuta es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'asistentes.required' => 'El campo "Asistentes" es obligatorio.',
            'asistentes.max' => 'El campo "Asistentes" no puede exceder 500 caracteres.',
            'puntos_tratados.required' => 'El campo "Puntos tratados" es obligatorio.',
            'puntos_tratados.max' => 'El campo "Puntos tratados" no puede exceder 5000 caracteres.',
            'acuerdos.required' => 'Debes agregar al menos un acuerdo.',
            'acuerdos.min' => 'Debes agregar al menos un acuerdo.',
            'acuerdos.*.acuerdo.required' => 'El texto del acuerdo es obligatorio en cada fila.',
            'acuerdos.*.acuerdo.max' => 'El texto del acuerdo no puede exceder 1000 caracteres.',
            'acuerdos.*.estatus.required' => 'El estatus es obligatorio en cada acuerdo.',
            'acuerdos.*.estatus.in' => 'El estatus debe ser "pendiente" o "completado".',
            'acuerdos.*.fecha_limite.date' => 'La fecha límite del acuerdo no es válida.',
        ]);

        DB::transaction(function () use ($request, $minuta) {
            $idsMantenidos = [];
            // array_values() asegura que procesamos la lista estrictamente en el orden visual
            $acuerdosOrdenados = array_values($request->acuerdos);

            foreach ($acuerdosOrdenados as $index => $item) {
                $acuerdoId = $item['id_acuerdo'] ?? null;

                $data = [
                    'acuerdo'       => $item['acuerdo'],
                    'responsable'   => $item['responsable'] ?? null,
                    'orden'         => $index, // Guardamos el orden visual correcto
                    'estatus'       => $item['estatus'],
                    'fecha_limite'  => $item['fecha_limite'] ?? null,
                ];

                if ($acuerdoId) {
                    // Actualizar acuerdo existente
                    $acuerdo = Acuerdo::findOrFail($acuerdoId);
                    $acuerdo->update($data);
                    $idsMantenidos[] = $acuerdoId;
                } else {
                    // Crear nuevo acuerdo
                    $acuerdo = $minuta->acuerdos()->create($data);
                    $idsMantenidos[] = $acuerdo->id_acuerdo;
                }
            }

            // Eliminar acuerdos que fueron removidos
            $minuta->acuerdos()
                ->whereNotIn('id_acuerdo', $idsMantenidos)
                ->delete();

            // Actualizar minuta
            $minuta->update([
                'id_cliente'        => $request->id_cliente,
                'titulo'            => $request->titulo,
                'fecha'             => $request->fecha,
                'asistentes'        => $request->asistentes,
                'puntos_tratados'   => $request->puntos_tratados,
                'observaciones'     => $request->observaciones,
            ]);
        });

        return redirect()->route('minutas.index')
            ->with('success', 'Minuta actualizada correctamente.');
    }

    public function destroy(Minuta $minuta)
    {
        // Eliminar manualmente todos los acuerdos relacionados
        $minuta->acuerdos()->delete();
        
        // Eliminar la minuta
        $minuta->delete();
        
        return redirect()->route('minutas.index')->with('success', 'Minuta eliminada.');
    }

    public function search(Request $request)
    {
        $queryText = $request->get('query');
        $folio     = $request->get('folio');
        $fecha     = $request->get('fecha');
        $estatus   = $request->get('estatus', 'todas');

        $sql = Minuta::with('cliente');

        // Filtro por estatus de acuerdos
        if ($estatus === 'completado') {
            $sql->whereHas('acuerdos')
                ->whereDoesntHave('acuerdos', function($q) {
                    $q->where('estatus', 'pendiente');
                });
        } elseif ($estatus !== 'todas') {
            $sql->whereHas('acuerdos', function($q) use ($estatus) {
                $q->where('estatus', $estatus);
            });
        }

        // Filtro por folio (id_minuta)
        if (!empty($folio)) {
            $sql->where('id_minuta', 'like', '%' . $folio . '%');
        }

        // Filtro por fecha exacta
        if (!empty($fecha)) {
            $sql->whereDate('fecha', '=', $fecha);
        }

        // Búsqueda por texto (título, cliente, empresa)
        if (!empty($queryText)) {
            $sql->where(function($q) use ($queryText) {
                // Buscar por título
                $q->where('titulo', 'like', '%' . $queryText . '%')
                  // Buscar por cliente (nombre, apellidos, empresa)
                  ->orWhereHas('cliente', function($subQ) use ($queryText) {
                      $subQ->where('nombre', 'like', '%' . $queryText . '%')
                           ->orWhere('apellido_paterno', 'like', '%' . $queryText . '%')
                           ->orWhere('apellido_materno', 'like', '%' . $queryText . '%')
                           ->orWhere('empresa', 'like', '%' . $queryText . '%');
                  });
            });
        }

        $minutas = $sql->orderBy('id_minuta', 'desc')->get();

        if ($minutas->count() > 0) {
            return view('minutas._tabla_minutas', compact('minutas'))->render();
        } else {
            return '<div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 text-gray-500"><p>No se encontraron minutas.</p></div>';
        }
    }
}

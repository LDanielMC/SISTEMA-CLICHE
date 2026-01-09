<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Asegúrate de tener instalado barryvdh/laravel-dompdf

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $estatusFilter = $request->query('estatus', 'todas');
        $sortBy = $request->query('sort_by', 'fecha');
        $sortDir = $request->query('sort_dir', 'desc');

        $query = Cotizacion::with('cliente');

        if ($estatusFilter !== 'todas') {
            $query->where('estatus', $estatusFilter);
        }

        $cotizaciones = $query->orderBy($sortBy, $sortDir)->paginate(10);

        return view('cotizaciones.index', compact('cotizaciones', 'estatusFilter', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        $clientes = Cliente::where('estatus', 'activo')->orderBy('nombre')->get();
        $nextId = Cotizacion::max('id_cotizacion') + 1;

        return view('cotizaciones.create', compact('clientes', 'nextId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo_cotizacion' => 'required|string|max:200',
            'id_cliente'        => 'required|exists:clientes,id_cliente',
            'fecha'             => 'required|date',
            'vencimiento_dias'  => 'required|integer|min:1',
            'texto_introduccion'=> 'nullable|string|max:1000',
            'estatus'           => 'required|in:pendiente,aceptada,rechazada',
            
            'partidas'               => 'required|array|min:1',
            'partidas.*.titulo'      => 'required|string|max:100',
            'partidas.*.cantidad'    => 'required|numeric|min:0.01',
            'partidas.*.descripcion' => 'required|string|max:400',
            'partidas.*.precio_unitario' => 'required|numeric|min:0',
            'partidas.*.iva'         => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            
            $subtotalGeneral = 0;
            $ivaGeneral = 0;
            $totalGeneral = 0;
            $detallesData = [];

            // Reseteamos los índices para guardar orden secuencial (0, 1, 2...)
            $partidasOrdenadas = array_values($request->partidas);

            foreach ($partidasOrdenadas as $index => $item) {
                $cantidad = floatval($item['cantidad']);
                $precio   = floatval($item['precio_unitario']);
                $ivaItem  = floatval($item['iva']); 

                $importeLinea = $cantidad * $precio;
                $totalLinea   = $importeLinea + $ivaItem;

                $subtotalGeneral += $importeLinea;
                $ivaGeneral      += $ivaItem;
                $totalGeneral    += $totalLinea;

                $detallesData[] = [
                    'orden'           => $index,
                    'titulo'          => $item['titulo'],
                    'cantidad'        => $cantidad,
                    'descripcion'     => $item['descripcion'],
                    'precio_unitario' => $precio,
                    'iva'             => $ivaItem,
                    'precio_total'    => $totalLinea
                ];
            }

            $cotizacion = Cotizacion::create([
                'titulo_cotizacion' => $request->titulo_cotizacion,
                'id_cliente'        => $request->id_cliente,
                'fecha'             => $request->fecha,
                'vencimiento_dias'  => $request->vencimiento_dias,
                'texto_introduccion'=> $request->texto_introduccion,
                'subtotal'          => $subtotalGeneral,
                'iva_total'         => $ivaGeneral,
                'total'             => $totalGeneral,
                'notas'             => $request->notas,
                'estatus'           => $request->estatus,
            ]);

            $cotizacion->detalles()->createMany($detallesData);
        });

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización creada correctamente.');
    }

    public function edit(Cotizacion $cotizacion)
    {
        $clientes = Cliente::where('estatus', 'activo')->orderBy('nombre')->get();
        
        // Cargamos los detalles ordenados por la columna 'orden'
        $cotizacion->load(['detalles' => function($query) {
            $query->orderBy('orden', 'asc');
        }]);
        
        return view('cotizaciones.edit', compact('cotizacion', 'clientes'));
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'titulo_cotizacion' => 'required|string|max:200',
            'id_cliente'        => 'required|exists:clientes,id_cliente',
            'fecha'             => 'required|date',
            'vencimiento_dias'  => 'required|integer|min:1',
            'texto_introduccion'=> 'nullable|string|max:1000',
            'estatus'           => 'required|in:pendiente,aceptada,rechazada',

            'partidas'                  => 'required|array|min:1',
            'partidas.*.id_detalle'     => 'nullable|integer',
            'partidas.*.titulo'         => 'required|string|max:100',
            'partidas.*.descripcion'    => 'nullable|string|max:400',
            'partidas.*.cantidad'       => 'required|numeric|min:0.01',
            'partidas.*.precio_unitario'=> 'required|numeric|min:0',
            'partidas.*.iva'            => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $cotizacion) {

            $subtotalGeneral = 0;
            $ivaGeneral = 0;
            $totalGeneral = 0;
            $idsMantenidos = [];

            // array_values asegura que procesamos la lista estrictamente en el orden visual
            $partidasOrdenadas = array_values($request->partidas);

            foreach ($partidasOrdenadas as $index => $item) {

                $cantidad = floatval($item['cantidad']);
                $precio   = floatval($item['precio_unitario']);
                $ivaItem  = floatval($item['iva'] ?? 0);

                $importe = $cantidad * $precio;
                $totalLinea = $importe + $ivaItem;

                $subtotalGeneral += $importe;
                $ivaGeneral      += $ivaItem;
                $totalGeneral    += $totalLinea;

                $data = [
                    'orden'           => $index, // Guardamos el orden visual correcto
                    'titulo'          => $item['titulo'],
                    'cantidad'        => $cantidad,
                    'descripcion'     => $item['descripcion'] ?? '',
                    'precio_unitario' => $precio,
                    'iva'             => $ivaItem,
                    'precio_total'    => $totalLinea,
                ];

                if (!empty($item['id_detalle'])) {
                    $detalle = $cotizacion->detalles()
                        ->where('id_detalle', $item['id_detalle'])
                        ->firstOrFail();

                    $detalle->update($data);
                    $idsMantenidos[] = $detalle->id_detalle;
                } else {
                    $detalle = $cotizacion->detalles()->create($data);
                    $idsMantenidos[] = $detalle->id_detalle;
                }
            }

            // Borrar eliminados
            $cotizacion->detalles()
                ->whereNotIn('id_detalle', $idsMantenidos)
                ->delete();

            // Actualizar totales y cabecera
            $cotizacion->update([
                'titulo_cotizacion' => $request->titulo_cotizacion,
                'id_cliente'        => $request->id_cliente,
                'fecha'             => $request->fecha,
                'vencimiento_dias'  => $request->vencimiento_dias,
                'texto_introduccion'=> $request->texto_introduccion,
                'subtotal'          => $subtotalGeneral,
                'iva_total'         => $ivaGeneral,
                'total'             => $totalGeneral,
                'notas'             => $request->notas,
                'estatus'           => $request->estatus,
            ]);
        });

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización actualizada correctamente.');
    }

    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada.');
    }

    public function search(Request $request)
    {
        $queryText = $request->get('query');
        $estatus   = $request->get('estatus', 'todas');

        $sql = Cotizacion::with('cliente');

        if ($estatus !== 'todas') {
            $sql->where('estatus', $estatus);
        }

        if (!empty($queryText)) {
            $sql->where(function($q) use ($queryText) {
                $q->where('id_cotizacion', 'LIKE', "%$queryText%")
                    ->orWhere('titulo_cotizacion', 'LIKE', "%$queryText%")
                    ->orWhereHas('cliente', function($c) use ($queryText){
                        $c->where('nombre', 'LIKE', "%$queryText%")
                            ->orWhere('empresa', 'LIKE', "%$queryText%");
                    });
            });
        }

        $cotizaciones = $sql->orderBy('fecha', 'desc')->get();

        if ($cotizaciones->count() > 0) {
            return view('cotizaciones._tabla_cotizaciones', compact('cotizaciones'))->render();
        } else {
            return '<div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 text-gray-500"><p>No se encontraron cotizaciones.</p></div>';
        }
    }
    
    // ✅ 2. ACTUALIZADO: Método para generar el PDF
    public function pdf(Cotizacion $cotizacion)
    {
        // Cargamos los datos necesarios.
        // IMPORTANTE: Ordenamos los detalles por 'orden' ascendente para que salgan igual que en la vista de edición.
        $cotizacion->load(['cliente', 'detalles' => function($q) {
            $q->orderBy('orden', 'asc');
        }]);

        // Cargamos la vista del PDF con los datos
        $pdf = Pdf::loadView('cotizaciones.pdf', compact('cotizacion'));

        // Configuramos tamaño de papel (A4 es estándar)
        $pdf->setPaper('A4', 'portrait');

        // 'stream' muestra el PDF en el navegador. Si prefieres que se descargue directo, usa 'download'.
        return $pdf->stream('Cotizacion-' . str_pad($cotizacion->id_cotizacion, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
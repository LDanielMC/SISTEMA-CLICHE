<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('Reporte de Acuerdos por Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Acciones -->
            <div class="flex justify-end items-center mb-6">
                <button onclick="exportarPNG()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Exportar PNG
                </button>
            </div>

            <!-- VISTA WEB (sin gráfica) -->
            <div id="web-view">

                <!-- Tarjetas de Resumen - KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg sm:rounded-lg p-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase opacity-90">Total Acuerdos</div>
                                <div class="mt-2 text-2xl font-bold">{{ $totalAcuerdosGeneral }}</div>
                            </div>
                            <div class="text-3xl opacity-80">📝</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase text-gray-500">Concluidos</div>
                                <div class="mt-2 text-2xl font-bold text-green-600">{{ $totalConcluidosGeneral }}</div>
                            </div>
                            <div class="text-3xl">✅</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase text-gray-500">Pendientes</div>
                                <div class="mt-2 text-2xl font-bold text-yellow-600">{{ $totalPendientesGeneral }}</div>
                            </div>
                            <div class="text-3xl">⏳</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-indigo-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase text-gray-500">% Cumplimiento</div>
                                <div class="mt-2 text-2xl font-bold text-indigo-600">{{ $porcentajeGeneral }}%</div>
                            </div>
                            <div class="text-3xl">📊</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Columna Izquierda: Filtro -->
                    <div class="lg:col-span-1">
                        <div class="bg-white shadow sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Filtro</h3>
                            <form action="{{ route('reportes.acuerdos_cliente') }}" method="GET">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                                    <select name="id_cliente" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Todos los clientes</option>
                                        @foreach($clientesLista as $cli)
                                            <option value="{{ $cli->id_cliente }}" {{ $idCliente == $cli->id_cliente ? 'selected' : '' }}>
                                                {{ $cli->empresa }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('reportes.acuerdos_cliente') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">Limpiar</a>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Columna Derecha: Tabla de Clientes -->
                    <div class="lg:col-span-2">
                        <div class="bg-white shadow sm:rounded-lg overflow-hidden mb-6">
                            <div class="px-6 py-4 bg-gray-50 border-b">
                                <h3 class="text-lg font-medium text-gray-900">Resumen por Cliente</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Concluidos</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pendientes</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">% Cumplimiento</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($datosClientes as $datos)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $datos['cliente']->empresa }}</div>
                                                    <div class="text-xs text-gray-500">{{ $datos['cliente']->nombre }} {{ $datos['cliente']->apellido_paterno }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="text-sm font-semibold text-gray-900">{{ $datos['total_acuerdos'] }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $datos['concluidos'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        {{ $datos['pendientes'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex items-center justify-center">
                                                        <div class="w-16">
                                                            <div class="text-xs font-bold text-gray-700">{{ $datos['porcentaje_concluido'] }}%</div>
                                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $datos['porcentaje_concluido'] }}%;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                                    No se encontraron clientes con acuerdos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Acuerdos Pendientes (si hay un cliente seleccionado) -->
                        @if($idCliente && $datosClientes->isNotEmpty())
                        @php $clienteData = $datosClientes->first(); @endphp
                        @if($clienteData['acuerdos_pendientes']->isNotEmpty())
                        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                            <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                                <h3 class="text-lg font-medium text-gray-900">⏳ Acuerdos Pendientes - {{ $clienteData['cliente']->empresa }}</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acuerdo</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Límite</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($clienteData['acuerdos_pendientes'] as $acuerdo)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">{{ $acuerdo->acuerdo }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $acuerdo->responsable }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm {{ $acuerdo->fecha_limite && $acuerdo->fecha_limite->isPast() ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                                        {{ $acuerdo->fecha_limite ? $acuerdo->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- VISTA PARA EXPORTACIÓN PNG (oculta, solo se muestra al exportar) -->
            <div id="export-view" style="display: none; position: fixed; left: -9999px; width: 1200px; background: white; padding: 40px;">
                <!-- Encabezado profesional -->
                <div style="text-align: center; margin-bottom: 30px; border-bottom: 3px solid #4f46e5; padding-bottom: 20px;">
                    <h1 style="font-size: 28px; color: #4f46e5; font-weight: bold; margin: 0;">📋 REPORTE DE ACUERDOS POR CLIENTE</h1>
                    <p style="font-size: 14px; color: #6b7280; margin-top: 8px;">Generado el {{ date('d/m/Y H:i') }}</p>
                    @if($idCliente && $datosClientes->isNotEmpty())
                    <p style="font-size: 16px; color: #374151; margin-top: 8px; font-weight: 600;">Cliente: {{ $datosClientes->first()['cliente']->empresa }}</p>
                    @endif
                </div>

                <!-- KPIs en Grid -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                    <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 20px; border-radius: 8px; color: white; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-bottom: 8px;">Total Acuerdos</div>
                        <div style="font-size: 32px; font-weight: bold;">{{ $idCliente && $datosClientes->isNotEmpty() ? $datosClientes->first()['total_acuerdos'] : $totalAcuerdosGeneral }}</div>
                    </div>
                    <div style="background: #10b981; padding: 20px; border-radius: 8px; color: white; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-bottom: 8px;">Concluidos</div>
                        <div style="font-size: 32px; font-weight: bold;">{{ $idCliente && $datosClientes->isNotEmpty() ? $datosClientes->first()['concluidos'] : $totalConcluidosGeneral }}</div>
                    </div>
                    <div style="background: #f59e0b; padding: 20px; border-radius: 8px; color: white; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-bottom: 8px;">Pendientes</div>
                        <div style="font-size: 32px; font-weight: bold;">{{ $idCliente && $datosClientes->isNotEmpty() ? $datosClientes->first()['pendientes'] : $totalPendientesGeneral }}</div>
                    </div>
                    <div style="background: #6366f1; padding: 20px; border-radius: 8px; color: white; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-bottom: 8px;">% Cumplimiento</div>
                        <div style="font-size: 32px; font-weight: bold;">{{ $idCliente && $datosClientes->isNotEmpty() ? $datosClientes->first()['porcentaje_concluido'] : $porcentajeGeneral }}%</div>
                    </div>
                </div>

                <!-- Sección con Gráfica y Tabla -->
                @if($idCliente && $datosGrafica)
                <div style="display: grid; grid-template-columns: 380px 1fr; gap: 30px; margin-bottom: 30px;">
                    <!-- Gráfica de Pastel -->
                    <div style="background: white; padding: 20px; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <h3 style="font-size: 16px; font-weight: bold; color: #374151; margin-bottom: 15px; text-align: center;">Distribución de Acuerdos</h3>
                        <div style="height: 280px;">
                            <canvas id="chartExport"></canvas>
                        </div>
                    </div>

                    <!-- Resumen del Cliente -->
                    <div style="background: #f9fafb; padding: 20px; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <h3 style="font-size: 16px; font-weight: bold; color: #374151; margin-bottom: 15px;">Resumen Ejecutivo</h3>
                        @php $clienteData = $datosClientes->first(); @endphp
                        <div style="font-size: 14px; line-height: 1.8; color: #4b5563;">
                            <p style="margin-bottom: 10px;"><strong>Cliente:</strong> {{ $clienteData['cliente']->empresa }}</p>
                            <p style="margin-bottom: 10px;"><strong>Contacto:</strong> {{ $clienteData['cliente']->nombre }} {{ $clienteData['cliente']->apellido_paterno }}</p>
                            <p style="margin-bottom: 10px;"><strong>Total de Acuerdos:</strong> {{ $clienteData['total_acuerdos'] }}</p>
                            <p style="margin-bottom: 10px;"><strong>Estado:</strong></p>
                            <ul style="margin-left: 20px; margin-top: 5px;">
                                <li style="color: #10b981;">✓ Concluidos: {{ $clienteData['concluidos'] }} ({{ $clienteData['porcentaje_concluido'] }}%)</li>
                                <li style="color: #f59e0b; margin-top: 5px;">⏳ Pendientes: {{ $clienteData['pendientes'] }} ({{ 100 - $clienteData['porcentaje_concluido'] }}%)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tabla de Acuerdos Pendientes -->
                @if($idCliente && $datosClientes->isNotEmpty())
                @php $clienteData = $datosClientes->first(); @endphp
                @if($clienteData['acuerdos_pendientes']->isNotEmpty())
                <div style="margin-top: 30px;">
                    <h3 style="font-size: 18px; font-weight: bold; color: #374151; margin-bottom: 15px; padding-left: 10px; border-left: 4px solid #f59e0b;">⏳ Acuerdos Pendientes</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: #f3f4f6;">
                                <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; color: #374151;">Acuerdo</th>
                                <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 200px;">Responsable</th>
                                <th style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 120px;">Fecha Límite</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clienteData['acuerdos_pendientes'] as $acuerdo)
                            <tr>
                                <td style="padding: 10px; border: 1px solid #e5e7eb; color: #1f2937;">{{ $acuerdo->acuerdo }}</td>
                                <td style="padding: 10px; border: 1px solid #e5e7eb; color: #1f2937;">{{ $acuerdo->responsable }}</td>
                                <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: center; {{ $acuerdo->fecha_limite && $acuerdo->fecha_limite->isPast() ? 'color: #dc2626; font-weight: bold;' : 'color: #1f2937;' }}">
                                    {{ $acuerdo->fecha_limite ? $acuerdo->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @else
                <!-- Tabla resumen de todos los clientes -->
                <div style="margin-top: 30px;">
                    <h3 style="font-size: 18px; font-weight: bold; color: #374151; margin-bottom: 15px; padding-left: 10px; border-left: 4px solid #4f46e5;">Resumen por Cliente</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: #f3f4f6;">
                                <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; color: #374151;">Cliente</th>
                                <th style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 100px;">Total</th>
                                <th style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 120px;">Concluidos</th>
                                <th style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 120px;">Pendientes</th>
                                <th style="padding: 12px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 120px;">% Cumplimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datosClientes as $datos)
                            <tr>
                                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                                    <div style="font-weight: 600; color: #1f2937;">{{ $datos['cliente']->empresa }}</div>
                                    <div style="font-size: 11px; color: #6b7280;">{{ $datos['cliente']->nombre }} {{ $datos['cliente']->apellido_paterno }}</div>
                                </td>
                                <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: center; font-weight: 600; color: #1f2937;">{{ $datos['total_acuerdos'] }}</td>
                                <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: center;">
                                    <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-weight: 600; font-size: 12px;">{{ $datos['concluidos'] }}</span>
                                </td>
                                <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: center;">
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-weight: 600; font-size: 12px;">{{ $datos['pendientes'] }}</span>
                                </td>
                                <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: center; font-weight: bold; color: #4f46e5; font-size: 14px;">{{ $datos['porcentaje_concluido'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Footer -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 12px;">
                    <p>Sistema de Gestión - Reporte generado automáticamente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- html2canvas para exportar PNG -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script>
        let chartExportInstance = null;

        // Función para crear la gráfica de exportación
        function crearGraficaExportacion() {
            @if($idCliente && $datosGrafica)
            const ctx = document.getElementById('chartExport');
            
            if (ctx && !chartExportInstance) {
                const data = @json($datosGrafica);
                
                chartExportInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(data),
                        datasets: [{
                            data: Object.values(data),
                            backgroundColor: ['#10B981', '#F59E0B'],
                            borderWidth: 3,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 15,
                                    padding: 15,
                                    font: { size: 14, weight: 'bold' }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) label += ': ';
                                        label += context.parsed + ' acuerdos';
                                        
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        label += ' (' + percentage + '%)';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
            @endif
        }

        // Función para exportar a PNG
        function exportarPNG() {
            const exportView = document.getElementById('export-view');
            
            // Mostrar mensaje de carga
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generando...';
            btn.disabled = true;
            
            // Mostrar la vista de exportación de manera visible pero fuera de pantalla
            exportView.style.display = 'block';
            exportView.style.position = 'fixed';
            exportView.style.left = '0';
            exportView.style.top = '-10000px';
            exportView.style.visibility = 'visible';
            exportView.style.opacity = '1';
            
            // Destruir gráfica anterior si existe
            if (chartExportInstance) {
                chartExportInstance.destroy();
                chartExportInstance = null;
            }
            
            // Pequeña pausa para que el DOM se actualice
            setTimeout(() => {
                // Crear la gráfica si existe
                crearGraficaExportacion();
                
                // Esperar más tiempo para que la gráfica se renderice completamente
                setTimeout(() => {
                    html2canvas(exportView, {
                        scale: 2,
                        backgroundColor: '#ffffff',
                        logging: true,
                        useCORS: true,
                        width: 1200,
                        height: exportView.scrollHeight,
                        windowWidth: 1200,
                        allowTaint: false
                    }).then(canvas => {
                        // Crear enlace de descarga
                        const link = document.createElement('a');
                        const clienteNombre = @json($idCliente && $datosClientes->isNotEmpty() ? $datosClientes->first()['cliente']->empresa : 'general');
                        const filename = 'reporte-acuerdos-' + clienteNombre.toLowerCase().replace(/\s+/g, '-') + '-' + new Date().toISOString().split('T')[0] + '.png';
                        link.download = filename;
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        
                        // Ocultar la vista de exportación
                        exportView.style.display = 'none';
                        
                        // Restaurar botón
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }).catch(error => {
                        console.error('Error al generar PNG:', error);
                        alert('Error al generar la imagen. Intenta nuevamente.');
                        exportView.style.display = 'none';
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
                }, 1000); // Aumentado a 1 segundo
            }, 100);
        }
    </script>
</x-app-layout>

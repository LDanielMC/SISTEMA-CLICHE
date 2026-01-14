<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Panel de administración
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Bienvenido al SGI de Cliché. Accesos rápidos a los módulos principales.
                </p>
            </div>

            {{-- Sutil “chip” de marca --}}
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/70 backdrop-blur border border-blue-100 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#004481]"></span>
                <span class="text-xs font-semibold tracking-wide text-blue-900/80">CLICHÉ SGI</span>
            </div>
        </div>
    </x-slot>

    {{-- Fondo sutil (NO saturado) --}}
    <div class="relative">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            {{-- manchitas MUY suaves --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-sky-200 blur-3xl opacity-35"></div>
            <div class="absolute -top-28 -right-24 w-[28rem] h-[28rem] rounded-full bg-indigo-200 blur-3xl opacity-30"></div>
            <div class="absolute -bottom-32 left-1/3 w-[36rem] h-[28rem] rounded-full bg-blue-200 blur-3xl opacity-30"></div>
        </div>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Contenedor tipo “glass” MUY leve --}}
                <div class="bg-white/60 backdrop-blur-sm border border-white/60 rounded-2xl shadow-sm p-4 sm:p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- ✅ Tarjeta Gestión de Usuarios (Empleados) --}}
                        <a href="{{ route('empleados.index') }}"
                           class="group block rounded-2xl border border-gray-200 bg-white/70 hover:bg-white transition shadow-sm hover:shadow-md overflow-hidden">
                            <div class="p-6 flex items-start gap-4">
                                <div class="shrink-0 w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center border border-blue-100">
                                    {{-- Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#004481]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H2v-2a4 4 0 014-4h1m9-4a4 4 0 10-8 0 4 4 0 008 0zm6 4a3 3 0 10-6 0 3 3 0 006 0z"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-[#004481] transition-colors">
                                        Gestión de usuarios (empleados)
                                    </h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        Ver, registrar, actualizar y eliminar información de empleados.
                                    </p>
                                </div>

                                <div class="text-gray-400 group-hover:text-[#004481] transition-colors">
                                    <span class="text-xl">&rsaquo;</span>
                                </div>
                            </div>
                            <div class="px-6 py-2 border-t border-gray-100 text-xs text-gray-500 bg-gray-50/60 flex justify-between">
                                <span>Administrar empleados</span>
                                <span class="text-[#004481] font-semibold group-hover:translate-x-1 transition-transform">Ir &rarr;</span>
                            </div>
                        </a>

                        {{-- ✅ Tarjeta Gestión de Clientes --}}
                        <a href="{{ route('clientes.index') }}"
                           class="group block rounded-2xl border border-gray-200 bg-white/70 hover:bg-white transition shadow-sm hover:shadow-md overflow-hidden">
                            <div class="p-6 flex items-start gap-4">
                                <div class="shrink-0 w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center border border-blue-100">
                                    {{-- Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#004481]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-2a4 4 0 014-4h10a4 4 0 014 4v2M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-[#004481] transition-colors">
                                        Gestión de clientes
                                    </h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        Administrar clientes de Cliché y su información básica.
                                    </p>
                                </div>

                                <div class="text-gray-400 group-hover:text-[#004481] transition-colors">
                                    <span class="text-xl">&rsaquo;</span>
                                </div>
                            </div>
                            <div class="px-6 py-2 border-t border-gray-100 text-xs text-gray-500 bg-gray-50/60 flex justify-between">
                                <span>Ver clientes</span>
                                <span class="text-[#004481] font-semibold group-hover:translate-x-1 transition-transform">Ir &rarr;</span>
                            </div>
                        </a>

                        {{-- ✅ Tarjeta Calendario de Publicaciones (NUEVO) --}}
                        <a href="{{ route('calendario.general') }}"
                           class="group block rounded-2xl border border-gray-200 bg-white/70 hover:bg-white transition shadow-sm hover:shadow-md overflow-hidden">
                            <div class="p-6 flex items-start gap-4">
                                <div class="shrink-0 w-11 h-11 rounded-xl bg-pink-50 flex items-center justify-center border border-pink-100">
                                    {{-- Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-pink-600 transition-colors">
                                        Calendario de Publicaciones
                                    </h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        Planificar, gestionar y visualizar el contenido de redes sociales.
                                    </p>
                                </div>

                                <div class="text-gray-400 group-hover:text-pink-600 transition-colors">
                                    <span class="text-xl">&rsaquo;</span>
                                </div>
                            </div>
                            <div class="px-6 py-2 border-t border-gray-100 text-xs text-gray-500 bg-gray-50/60 flex justify-between">
                                <span>Ver calendario</span>
                                <span class="text-pink-600 font-semibold group-hover:translate-x-1 transition-transform">Ir &rarr;</span>
                            </div>
                        </a>

                        {{-- ✅ Tarjeta Cotizaciones --}}
                        <a href="{{ route('cotizaciones.index') }}" class="group block">
                            <div class="rounded-2xl bg-white/70 hover:bg-white overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
                                <div class="p-6 text-gray-900 flex items-center justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="shrink-0 w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center border border-emerald-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>

                                        <div>
                                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-emerald-600 transition-colors">
                                                Cotizaciones
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Crear, enviar y gestionar presupuestos.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-gray-400 group-hover:text-emerald-600 transition-colors">
                                        <span class="text-xl">&rsaquo;</span>
                                    </div>
                                </div>

                                <div class="bg-gray-50/60 px-6 py-2 border-t border-gray-100 text-xs text-gray-500 flex justify-between">
                                    <span>Ver historial</span>
                                    <span class="text-emerald-600 font-semibold group-hover:translate-x-1 transition-transform">Ir ahora &rarr;</span>
                                </div>
                            </div>
                        </a>

                        {{-- ✅ Tarjeta Categorías --}}
                        <a href="{{ route('categorias.index') }}"
                           class="group block rounded-2xl border border-gray-200 bg-white/70 hover:bg-white transition shadow-sm hover:shadow-md overflow-hidden">
                            <div class="p-6 flex items-start gap-4">
                                <div class="shrink-0 w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center border border-purple-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-purple-600 transition-colors">
                                        Catálogo de categorías
                                    </h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        Gestionar categorías para clasificación de tareas.
                                    </p>
                                </div>

                                <div class="text-gray-400 group-hover:text-purple-600 transition-colors">
                                    <span class="text-xl">&rsaquo;</span>
                                </div>
                            </div>
                            <div class="px-6 py-2 border-t border-gray-100 text-xs text-gray-500 bg-gray-50/60 flex justify-between">
                                <span>Administrar categorías</span>
                                <span class="text-purple-600 font-semibold group-hover:translate-x-1 transition-transform">Ir &rarr;</span>
                            </div>
                        </a>

                        {{-- ✅ Tarjeta Tareas --}}
                        <a href="{{ route('tareas.index') }}"
                           class="group block rounded-2xl border border-gray-200 bg-white/70 hover:bg-white transition shadow-sm hover:shadow-md overflow-hidden">
                            <div class="p-6 flex items-start gap-4">
                                <div class="shrink-0 w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center border border-orange-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-orange-600 transition-colors">
                                        Gestión de tareas
                                    </h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        Organizar y administrar tareas internas del equipo.
                                    </p>
                                </div>

                                <div class="text-gray-400 group-hover:text-orange-600 transition-colors">
                                    <span class="text-xl">&rsaquo;</span>
                                </div>
                            </div>
                            <div class="px-6 py-2 border-t border-gray-100 text-xs text-gray-500 bg-gray-50/60 flex justify-between">
                                <span>Ver tareas</span>
                                <span class="text-orange-600 font-semibold group-hover:translate-x-1 transition-transform">Ir &rarr;</span>
                            </div>
                        </a>

                        {{-- ✅ Tarjeta Asignaciones --}}
                        <a href="{{ route('asignaciones.index') }}"
                           class="group block rounded-2xl border border-gray-200 bg-white/70 hover:bg-white transition shadow-sm hover:shadow-md overflow-hidden">
                            <div class="p-6 flex items-start gap-4">
                                <div class="shrink-0 w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center border border-indigo-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                        Asignación de tareas
                                    </h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        Asignar tareas a empleados y evaluar su desempeño.
                                    </p>
                                </div>

                                <div class="text-gray-400 group-hover:text-indigo-600 transition-colors">
                                    <span class="text-xl">&rsaquo;</span>
                                </div>
                            </div>
                            <div class="px-6 py-2 border-t border-gray-100 text-xs text-gray-500 bg-gray-50/60 flex justify-between">
                                <span>Gestionar asignaciones</span>
                                <span class="text-indigo-600 font-semibold group-hover:translate-x-1 transition-transform">Ir &rarr;</span>
                            </div>
                        </a>

                    </div>

                    {{-- footer mini opcional --}}
                    <div class="mt-6 flex items-center justify-between text-xs text-gray-500">
                        <span>Último acceso: {{ now()->format('d/m/Y H:i') }}</span>
                        <span class="font-semibold text-blue-900/70">Cliché SGI</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<nav x-data="{ open: false }"
     class="relative z-[9999] border-b border-white/60 bg-white/60 backdrop-blur-md">
    
    {{-- Fondo sutil tipo “acuarela” como el login (MUY suave) --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-10 -left-16 w-72 h-72 rounded-full bg-sky-200 blur-3xl opacity-30"></div>
        <div class="absolute -top-16 left-1/2 w-80 h-80 rounded-full bg-indigo-200 blur-3xl opacity-25 -translate-x-1/2"></div>
        <div class="absolute -top-10 -right-16 w-72 h-72 rounded-full bg-blue-200 blur-3xl opacity-25"></div>
    </div>

    {{-- Línea de marca (como tu azul) --}}
    <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-[#0149a8]"></div>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24">
            <div class="flex items-center">
                <!-- Logo Corporativo Cliché -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <img src="/img/logo-cliche.png"
                             alt="Cliché Marketing Digital"
                             class="block h-16 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    @if(Auth::user()->rol == 'admin')

                        <x-nav-link
                            :href="route('admin.dashboard')"
                            :active="request()->routeIs('admin.dashboard')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('admin.dashboard') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Inicio') }}
                        </x-nav-link>

                        {{-- Dropdown de Empleados --}}
                        <div class="relative sm:flex sm:items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition focus:outline-none {{ (request()->routeIs('empleados.*') || request()->routeIs('categorias.*') || request()->routeIs('tareas.*') || request()->routeIs('asignaciones.*')) ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                                        <div>{{ __('Empleados') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('empleados.index')">
                                        {{ __('Gestión de Empleados') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('categorias.index')">
                                        {{ __('Catálogo Tareas') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('tareas.index')">
                                        {{ __('Gestión de Tareas') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('asignaciones.index')">
                                        {{ __('Asignación de Tareas') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        {{-- Dropdown de Clientes --}}
                        <div class="relative sm:flex sm:items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition focus:outline-none {{ (request()->routeIs('clientes.*') || request()->routeIs('cotizaciones.*') || request()->routeIs('calendario.general') || request()->routeIs('minutas.*') || request()->routeIs('briefs.*')) ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                                        <div>{{ __('Clientes') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('clientes.index')">
                                        {{ __('Gestión de Clientes') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('cotizaciones.index')">
                                        {{ __('Cotizaciones') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('calendario.general')">
                                        📅 {{ __('Calendario de Publicaciones') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('minutas.index')">
                                        {{ __('Minutas') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('briefs.index')">
                                        📋 {{ __('Briefs') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <x-nav-link
                            :href="route('suscripciones.index')"
                            :active="request()->routeIs('suscripciones.*') || request()->routeIs('categorias-suscripcion.*')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ (request()->routeIs('suscripciones.*') || request()->routeIs('categorias-suscripcion.*')) ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Suscripciones') }}
                        </x-nav-link>

                        <x-nav-link
                            :href="route('eventos.index')"
                            :active="request()->routeIs('eventos.*')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('eventos.*') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            📅 {{ __('Calendario de Eventos') }}
                        </x-nav-link>

                        <x-nav-link
                            :href="route('backups.index')"
                            :active="request()->routeIs('backups.*')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('backups.*') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            💾 {{ __('Respaldos') }}
                        </x-nav-link>

                        {{-- Dropdown de Reportes --}}
                        <div class="relative sm:flex sm:items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition focus:outline-none {{ request()->routeIs('reportes.*') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                                        <div>📈 {{ __('Reportes') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('reportes.cumplimiento')">
                                        {{ __('Cumplimiento y Puntualidad') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('reportes.efectividad')">
                                        {{ __('Efectividad Cotizaciones') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('reportes.carga_trabajo')">
                                        {{ __('Carga de Trabajo') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('reportes.suscripciones')">
                                        {{ __('Suscripciones y Costos') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('reportes.acuerdos_cliente')">
                                        {{ __('Acuerdos por Cliente') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('reportes.crecimiento_clientes')">
                                        {{ __('Crecimiento de Clientes') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                    @endif

                    @if(Auth::user()->rol == 'cliente')
                        <x-nav-link
                            :href="route('cliente.dashboard')"
                            :active="request()->routeIs('cliente.dashboard')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('cliente.dashboard') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Calendario') }}
                        </x-nav-link>

                        <x-nav-link
                            :href="route('cliente.briefs')"
                            :active="request()->routeIs('cliente.briefs')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('cliente.briefs') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Briefs y Encuestas') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->rol == 'empleado')
                        <x-nav-link
                            :href="route('asignaciones.misTareas')"
                            :active="request()->routeIs('asignaciones.misTareas')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('asignaciones.misTareas') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Mis Tareas') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (Perfil de Usuario) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                {{-- Campanita de Notificaciones --}}
                <div x-data="notificaciones()" x-init="init()" class="relative">
                    <button @click="toggleDropdown()"
                            class="campanita-notificaciones relative inline-flex items-center px-3 py-2 rounded-xl bg-white/60 hover:bg-white/80 border border-blue-100 shadow-sm text-[#0149a8] hover:text-[#00337a] focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="noLeidasCount > 0"
                              x-text="noLeidasCount"
                              class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[20px] animate-pulse">
                        </span>
                    </button>

                    {{-- Dropdown de notificaciones --}}
                    <div x-show="dropdownOpen"
                         @click.away="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-[9999] max-h-[600px] overflow-hidden flex flex-col"
                         style="display: none;">

                        {{-- Header --}}
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900">Notificaciones</h3>
                            <button @click="marcarTodasLeidas()"
                                    x-show="noLeidasCount > 0"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-semibold">
                                Marcar todas como leídas
                            </button>
                        </div>

                        {{-- Lista de notificaciones --}}
                        <div class="overflow-y-auto flex-1" style="max-height: 500px;">
                            <template x-if="notificaciones.length === 0">
                                <div class="px-4 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No tienes notificaciones</p>
                                </div>
                            </template>

                            <template x-for="notif in notificaciones" :key="notif.id">
                                <div @click="clickNotificacion(notif)"
                                     :class="!notif.leida ? 'bg-blue-50 border-l-4 border-blue-500' : 'bg-white hover:bg-gray-50'"
                                     class="px-4 py-3 border-b border-gray-100 cursor-pointer transition">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <span x-text="getIcono(notif.tipo)" class="text-2xl"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900" x-text="notif.titulo"></p>
                                            <p class="text-xs text-gray-600 mt-1" x-text="notif.mensaje"></p>
                                            <p class="text-xs text-gray-400 mt-1" x-text="formatearFecha(notif.created_at)"></p>
                                        </div>
                                        <button @click.stop="eliminarNotificacion(notif.id)"
                                                class="flex-shrink-0 text-gray-400 hover:text-red-600 transition">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- ... tu resto del archivo se queda IGUAL ... --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-2 rounded-xl
                                       bg-white/60 hover:bg-white/80 border border-blue-100 shadow-sm
                                       text-[#0149a8] hover:text-[#00337a]
                                       focus:outline-none transition ease-in-out duration-150">
                            <div class="text-right leading-tight">
                                @if(Auth::user()->rol == 'admin')
                                    <span class="block text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Administrador</span>
                                    <span class="block text-sm font-bold">{{ Auth::user()->name }}</span>
                                @elseif(Auth::user()->rol == 'empleado')
                                    <span class="block text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Team Cliché</span>
                                    <span class="block text-sm font-bold">
                                        @if(Auth::user()->empleado)
                                            {{ Auth::user()->empleado->nombre }} {{ Auth::user()->empleado->apellido_paterno }}
                                        @else
                                            {{ Auth::user()->name }}
                                        @endif
                                    </span>
                                @elseif(Auth::user()->rol == 'cliente')
                                    <span class="block text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Cliente</span>
                                    <span class="block text-sm font-bold">
                                        @if(Auth::user()->cliente && Auth::user()->cliente->empresa)
                                            {{ Auth::user()->cliente->empresa }}
                                        @elseif(Auth::user()->cliente)
                                            {{ Auth::user()->cliente->nombre }} {{ Auth::user()->cliente->apellido_paterno }}
                                        @else
                                            {{ Auth::user()->name }}
                                        @endif
                                    </span>
                                @else
                                    <span class="block text-sm font-bold">{{ Auth::user()->email }}</span>
                                @endif
                            </div>

                            <div class="ms-1 opacity-80">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Móvil) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-xl
                               text-[#0149a8] bg-white/60 border border-blue-100 shadow-sm
                               hover:bg-white/80 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Móvil) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/60 bg-white/70 backdrop-blur-md">

        <div class="pt-2 pb-3 space-y-1 px-3">

            @if(Auth::user()->rol == 'admin')

                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Inicio') }}
                </x-responsive-nav-link>

                {{-- Grupo: Empleados --}}
                <div class="pt-2 pb-1 px-1">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-[#0149a8]/60 px-2 mb-1">Empleados</p>
                    <x-responsive-nav-link :href="route('empleados.index')" :active="request()->routeIs('empleados.*')">
                        {{ __('Gestión de Empleados') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('categorias.index')" :active="request()->routeIs('categorias.*')">
                        {{ __('Catálogo Tareas') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tareas.index')" :active="request()->routeIs('tareas.*')">
                        {{ __('Gestión de Tareas') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('asignaciones.index')" :active="request()->routeIs('asignaciones.*')">
                        {{ __('Asignación de Tareas') }}
                    </x-responsive-nav-link>
                </div>

                {{-- Grupo: Clientes --}}
                <div class="pt-2 pb-1 px-1">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-[#0149a8]/60 px-2 mb-1">Clientes</p>
                    <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                        {{ __('Gestión de Clientes') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('cotizaciones.index')" :active="request()->routeIs('cotizaciones.*')">
                        {{ __('Cotizaciones') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('calendario.general')" :active="request()->routeIs('calendario.general')">
                        📅 {{ __('Calendario de Publicaciones') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('minutas.index')" :active="request()->routeIs('minutas.*')">
                        {{ __('Minutas') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('briefs.index')" :active="request()->routeIs('briefs.*')">
                        📋 {{ __('Briefs') }}
                    </x-responsive-nav-link>
                </div>

                {{-- Grupo: Administración --}}
                <div class="pt-2 pb-1 px-1">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-[#0149a8]/60 px-2 mb-1">Administración</p>
                    <x-responsive-nav-link :href="route('suscripciones.index')" :active="request()->routeIs('suscripciones.*') || request()->routeIs('categorias-suscripcion.*')">
                        {{ __('Suscripciones') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('eventos.index')" :active="request()->routeIs('eventos.*')">
                        📅 {{ __('Calendario de Eventos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('backups.index')" :active="request()->routeIs('backups.*')">
                        💾 {{ __('Respaldos') }}
                    </x-responsive-nav-link>
                </div>

                {{-- Grupo: Reportes --}}
                <div class="pt-2 pb-1 px-1">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-[#0149a8]/60 px-2 mb-1">📈 Reportes</p>
                    <x-responsive-nav-link :href="route('reportes.cumplimiento')" :active="request()->routeIs('reportes.cumplimiento')">
                        {{ __('Cumplimiento y Puntualidad') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reportes.efectividad')" :active="request()->routeIs('reportes.efectividad')">
                        {{ __('Efectividad Cotizaciones') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reportes.carga_trabajo')" :active="request()->routeIs('reportes.carga_trabajo')">
                        {{ __('Carga de Trabajo') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reportes.suscripciones')" :active="request()->routeIs('reportes.suscripciones')">
                        {{ __('Suscripciones y Costos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reportes.acuerdos_cliente')" :active="request()->routeIs('reportes.acuerdos_cliente')">
                        {{ __('Acuerdos por Cliente') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reportes.crecimiento_clientes')" :active="request()->routeIs('reportes.crecimiento_clientes')">
                        {{ __('Crecimiento de Clientes') }}
                    </x-responsive-nav-link>
                </div>

            @endif

            @if(Auth::user()->rol == 'cliente')
                <x-responsive-nav-link :href="route('cliente.dashboard')" :active="request()->routeIs('cliente.dashboard')">
                    {{ __('Calendario') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cliente.briefs')" :active="request()->routeIs('cliente.briefs')">
                    {{ __('Briefs y Encuestas') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->rol == 'empleado')
                <x-responsive-nav-link :href="route('asignaciones.misTareas')" :active="request()->routeIs('asignaciones.misTareas')">
                    {{ __('Mis Tareas') }}
                </x-responsive-nav-link>
            @endif

        </div>

        {{-- Perfil / Cerrar Sesión --}}
        <div class="pt-4 pb-3 border-t border-white/60 px-3">
            <div class="px-2 mb-2">
                @if(Auth::user()->rol == 'admin')
                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Administrador</div>
                    <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                @elseif(Auth::user()->rol == 'empleado')
                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Team Cliché</div>
                    <div class="text-sm font-bold text-gray-800">
                        {{ Auth::user()->empleado?->nombre ?? Auth::user()->name }}
                        {{ Auth::user()->empleado?->apellido_paterno ?? '' }}
                    </div>
                @elseif(Auth::user()->rol == 'cliente')
                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Cliente</div>
                    <div class="text-sm font-bold text-gray-800">
                        {{ Auth::user()->cliente?->empresa ?? Auth::user()->cliente?->nombre ?? Auth::user()->name }}
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Cerrar Sesión') }}
                </x-responsive-nav-link>
            </form>
        </div>

    </div>
</nav>

@push('scripts')
<script>
    function notificaciones() {
        return {
            notificaciones: [],
            noLeidasCount: 0,
            dropdownOpen: false,
            
            init() {
                this.cargarNotificaciones();
                setInterval(() => {
                    this.cargarNoLeidas();
                    if (this.dropdownOpen) {
                        this.cargarNotificaciones();
                    }
                }, 5000);
            },

            async cargarNotificaciones() {
                try {
                    const response = await fetch('/notificaciones', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await response.json();
                    this.notificaciones = data;
                    this.noLeidasCount = data.filter(n => !n.leida).length;
                } catch (error) {
                    console.error('Error al cargar notificaciones:', error);
                }
            },

            async cargarNoLeidas() {
                try {
                    const response = await fetch('/notificaciones/no-leidas', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await response.json();
                    const countAnterior = this.noLeidasCount;
                    this.noLeidasCount = data.count;

                    if (data.count > countAnterior) {
                        this.cargarNotificaciones();
                        this.animarCampanita();
                        this.mostrarToastNuevaNotificacion();
                    }
                } catch (error) {
                    console.error('Error al cargar contador:', error);
                }
            },

            animarCampanita() {
                const campanita = document.querySelector('.campanita-notificaciones');
                if (campanita) {
                    campanita.classList.add('animate-bounce');
                    setTimeout(() => {
                        campanita.classList.remove('animate-bounce');
                    }, 1000);
                }
            },

            mostrarToastNuevaNotificacion() {
                const toast = document.createElement('div');

                toast.className = 'fixed right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-xl flex items-center animate-slide-in';
                toast.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    <span>Nueva notificación recibida</span>
                `;

                // ✅ Debajo del nav (96px) + 16px de aire = 112px
                toast.style.top = '112px';

                // ✅ Z-index REAL (sin depender de Tailwind)
                toast.style.zIndex = '2147483647';

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100px)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            },

            toggleDropdown() {
                this.dropdownOpen = !this.dropdownOpen;
                if (this.dropdownOpen) {
                    this.cargarNotificaciones();
                }
            },

            async clickNotificacion(notif) {
                if (!notif.leida) {
                    await this.marcarLeida(notif.id);
                }

                const userRol = '{{ auth()->user()->rol }}';

                // Redirección basada en datos de la notificación
                if (notif.url) {
                    window.open(notif.url, '_blank'); // Abrir URL externa (ej. Google Forms)
                    return;
                }

                if (userRol === 'administrador' || userRol === 'admin') {
                    if (notif.asignacion_tarea && notif.asignacion_tarea.empleado) {
                        const empleadoId = notif.asignacion_tarea.empleado.id_empleado;
                        window.location.href = `/asignaciones/empleado/${empleadoId}`;
                    } else {
                        window.location.href = '/asignaciones';
                    }
                } else if (userRol === 'empleado') {
                    window.location.href = '/mis-tareas';
                } else if (userRol === 'cliente') {
                     window.location.href = '/cliente/dashboard';
                }
            },

            async marcarLeida(id) {
                try {
                    await fetch(`/notificaciones/${id}/marcar-leida`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const notif = this.notificaciones.find(n => n.id === id);
                    if (notif) {
                        notif.leida = true;
                        this.noLeidasCount = Math.max(0, this.noLeidasCount - 1);
                    }
                } catch (error) {
                    console.error('Error al marcar como leída:', error);
                }
            },

            async marcarTodasLeidas() {
                try {
                    await fetch('/notificaciones/marcar-todas-leidas', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    this.notificaciones.forEach(n => n.leida = true);
                    this.noLeidasCount = 0;
                } catch (error) {
                    console.error('Error al marcar todas como leídas:', error);
                }
            },

            async eliminarNotificacion(id) {
                try {
                    await fetch(`/notificaciones/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const index = this.notificaciones.findIndex(n => n.id === id);
                    if (index !== -1) {
                        if (!this.notificaciones[index].leida) {
                            this.noLeidasCount = Math.max(0, this.noLeidasCount - 1);
                        }
                        this.notificaciones.splice(index, 1);
                    }
                } catch (error) {
                    console.error('Error al eliminar notificación:', error);
                }
            },

            getIcono(tipo) {
                const iconos = {
                    'tarea_asignada': '📋',
                    'tarea_en_proceso': '⚙️',
                    'tarea_terminada': '✅',
                    'tarea_evaluada_completa': '🎉',
                    'tarea_evaluada_parcial': '⚠️',
                    'tarea_evaluada_incompleta': '❌',
                    'tarea_evaluada': '📊',
                    'alerta': '⏰'
                };
                return iconos[tipo] || '🔔';
            },

            formatearFecha(fecha) {
                const date = new Date(fecha);
                const ahora = new Date();
                const diff = Math.floor((ahora - date) / 1000);

                if (diff < 60) return 'Ahora';
                if (diff < 3600) return `Hace ${Math.floor(diff / 60)} min`;
                if (diff < 86400) return `Hace ${Math.floor(diff / 3600)} h`;
                if (diff < 604800) return `Hace ${Math.floor(diff / 86400)} días`;

                return date.toLocaleDateString('es-MX', {
                    day: 'numeric',
                    month: 'short',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }
    }
</script>
@endpush

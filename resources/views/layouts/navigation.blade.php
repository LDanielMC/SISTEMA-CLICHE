<nav x-data="{ open: false }"
     class="relative border-b border-white/60 bg-white/60 backdrop-blur-md">
    
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

                        <x-nav-link
                            :href="route('empleados.index')"
                            :active="request()->routeIs('empleados.*')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('empleados.*') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Empleados') }}
                        </x-nav-link>

                        {{-- Dropdown de Clientes (Incluye Cotizaciones) --}}
                        <div class="relative sm:flex sm:items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition focus:outline-none {{ (request()->routeIs('clientes.*') || request()->routeIs('cotizaciones.*')) ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
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
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <x-nav-link
                            :href="route('categorias.index')"
                            :active="request()->routeIs('categorias.*')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('categorias.*') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Categorías') }}
                        </x-nav-link>

                        <x-nav-link
                            :href="route('tareas.index')"
                            :active="request()->routeIs('tareas.*')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('tareas.*') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Tareas') }}
                        </x-nav-link>

                        <x-nav-link
                            :href="route('asignaciones.index')"
                            :active="request()->routeIs('asignaciones.*')"
                            class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-[#0149a8] hover:bg-white/60 transition
                                   {{ request()->routeIs('asignaciones.*') ? 'bg-white/70 text-[#0149a8] shadow-sm border border-blue-100' : '' }}">
                            {{ __('Asignaciones') }}
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
                         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-[600px] overflow-hidden flex flex-col"
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

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-2 rounded-xl
                                       bg-white/60 hover:bg-white/80 border border-blue-100 shadow-sm
                                       text-[#0149a8] hover:text-[#00337a]
                                       focus:outline-none transition ease-in-out duration-150">
                            
                            {{--  Avatar circle
                            <div class="w-9 h-9 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center">
                                <span class="text-xs font-bold text-[#0149a8]">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                                </span>
                            </div>  --}}

                            <!-- Información del Usuario -->
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
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-semibold
                          {{ request()->routeIs('admin.dashboard') ? 'bg-white/80 border border-blue-100 text-[#0149a8] shadow-sm' : 'text-gray-700 hover:bg-white/70' }}">
                    {{ __('Inicio') }}
                </a>

                <a href="{{ route('empleados.index') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-semibold
                          {{ request()->routeIs('empleados.*') ? 'bg-white/80 border border-blue-100 text-[#0149a8] shadow-sm' : 'text-gray-700 hover:bg-white/70' }}">
                    {{ __('Empleados') }}
                </a>

                {{-- Clientes en Móvil --}}
                <a href="{{ route('clientes.index') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-semibold
                          {{ request()->routeIs('clientes.index') ? 'bg-white/80 border border-blue-100 text-[#0149a8] shadow-sm' : 'text-gray-700 hover:bg-white/70' }}">
                    {{ __('Clientes (Listado)') }}
                </a>

                {{-- Cotizaciones en Móvil (Indentado para jerarquía) --}}
                <a href="{{ route('cotizaciones.index') }}"
                   class="block px-4 py-3 ml-4 rounded-xl text-sm font-semibold
                          {{ request()->routeIs('cotizaciones.*') ? 'bg-white/80 border border-blue-100 text-[#0149a8] shadow-sm' : 'text-gray-600 hover:bg-white/70' }}">
                    ↳ {{ __('Cotizaciones') }}
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-white/60">
            <div class="px-4">
                <div class="font-bold text-base text-[#0149a8]">
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 px-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold
                                   bg-white/60 hover:bg-white/80 border border-blue-100 shadow-sm
                                   text-gray-700 hover:text-[#0149a8] transition">
                        {{ __('Cerrar Sesión') }}
                    </button>
                </form>
            </div>
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
                // Polling inteligente cada 5 segundos
                setInterval(() => {
                    this.cargarNoLeidas();
                    // Si el dropdown está abierto, actualizar la lista también
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
                    
                    // Si hay nuevas notificaciones, recargar la lista y mostrar animación
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
                toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-xl z-[70] flex items-center animate-slide-in';
                toast.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    <span>Nueva notificación recibida</span>
                `;
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
                console.log('Click en notificación:', notif);
                
                // Marcar como leída
                if (!notif.leida) {
                    await this.marcarLeida(notif.id);
                }
                
                // Redirigir según el tipo de usuario y notificación
                const userRol = '{{ auth()->user()->rol }}';
                console.log('Rol del usuario:', userRol);
                
                if (userRol === 'administrador' || userRol === 'admin') {
                    // Admin: ir al tablero kanban del empleado
                    if (notif.asignacion_tarea && notif.asignacion_tarea.empleado) {
                        const empleadoId = notif.asignacion_tarea.empleado.id_empleado;
                        console.log('Redirigiendo a empleado:', empleadoId);
                        window.location.href = `/asignaciones/empleado/${empleadoId}`;
                    } else {
                        console.error('No se encontró información del empleado en la notificación');
                        window.location.href = '/asignaciones';
                    }
                } else if (userRol === 'empleado') {
                    // Empleado: ir a mis tareas
                    console.log('Redirigiendo a mis tareas');
                    window.location.href = '/mis-tareas';
                } else {
                    console.error('Rol no reconocido:', userRol);
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
                    
                    // Actualizar localmente
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
                    
                    // Actualizar localmente
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
                    
                    // Remover localmente
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
                    'tarea_evaluada': '📊'
                };
                return iconos[tipo] || '🔔';
            },
            
            formatearFecha(fecha) {
                const date = new Date(fecha);
                const ahora = new Date();
                const diff = Math.floor((ahora - date) / 1000); // segundos
                
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
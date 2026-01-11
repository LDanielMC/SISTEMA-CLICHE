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
            <div class="hidden sm:flex sm:items-center sm:ms-6">
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
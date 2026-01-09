<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SGI - Cliché</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:300,400,500,600|playfair-display:400,500,600,700" rel="stylesheet" />

    <!-- ✅ Vite (Tailwind + JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ✅ IMPORTANTÍSIMO: Estas clases SIEMPRE deben existir (aunque esté Vite activo) -->
    <style>
        :root{
            --font-sans: 'Instrument Sans', sans-serif;
            --font-serif: 'Playfair Display', serif;
        }

        /* ✅ Marca */
        .bg-blue-brand { background-color: #004481 !important; }
        .text-blue-brand { color: #004481 !important; }
        .border-blue-brand { border-color: #004481 !important; }
        .hover\:bg-blue-hover:hover { background-color: #003366 !important; }

        /* ✅ Acuarela (azules intensos) */
        .bg-watercolor-1 { background-color: #0ea5e9; } /* Sky 500 */
        .bg-watercolor-2 { background-color: #3b82f6; } /* Blue 500 */
        .bg-watercolor-3 { background-color: #6366f1; } /* Indigo 500 */

        /* ✅ Animación (para que sí se mueva aunque Tailwind no la tenga) */
        @keyframes float {
            0%   { transform: translate(0px, 0px) scale(1); }
            33%  { transform: translate(30px, -50px) scale(1.1); }
            66%  { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-float { animation: float 12s ease-in-out infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* ✅ Si el mix-blend apaga colores, lo normalizamos */
        .mix-blend-normal { mix-blend-mode: normal; }
    </style>
</head>

<body class="font-sans antialiased h-screen w-full overflow-hidden relative bg-white">

    <!-- ✅ Fondo de Acuarela (AHORA SÍ se ve con Vite activo) -->
    <div class="fixed inset-0 z-0 overflow-hidden bg-white">
        <!-- Mancha 1: Azul cielo -->
        <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-watercolor-1 blur-3xl opacity-70 animate-float mix-blend-normal"></div>

        <!-- Mancha 2: Índigo -->
        <div class="absolute top-[-10%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-watercolor-3 blur-3xl opacity-70 animate-float animation-delay-2000 mix-blend-normal"></div>

        <!-- Mancha 3: Azul medio -->
        <div class="absolute bottom-[-20%] left-[20%] w-[70vw] h-[50vw] rounded-full bg-watercolor-2 blur-3xl opacity-80 animate-float animation-delay-4000 mix-blend-normal"></div>
    </div>

    <!-- Contenido Principal -->
    <div class="relative z-10 flex flex-col items-center justify-center h-full px-4">

        <!-- Tarjeta Central -->
        <div class="bg-white/60 backdrop-blur-md p-12 rounded-3xl shadow-xl border border-white/50 max-w-md w-full relative overflow-hidden">

            <!-- Decoración de fondo interna -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-blue-brand rounded-full blur-3xl opacity-10 pointer-events-none"></div>

            <!-- Branding -->
            <div class="text-center mb-10 relative">
                <h2 class="font-serif text-4xl font-bold tracking-tight text-blue-brand">CLICHÉ</h2>
                <div class="flex items-center justify-center gap-3 mt-3">
                    <span class="h-[1px] w-8 bg-blue-brand opacity-30"></span>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-blue-900 font-semibold">Sistema Interno</p>
                    <span class="h-[1px] w-8 bg-blue-brand opacity-30"></span>
                </div>
            </div>

            <!-- Ilustración Abstracta -->
            <div class="flex justify-center mb-8">
                <div class="relative w-16 h-16 flex items-center justify-center">
                    <div class="absolute inset-0 border border-blue-brand/20 rounded-full animate-float"></div>
                    <div class="absolute inset-2 border border-blue-brand/40 rounded-full animate-float animation-delay-2000"></div>
                    <div class="w-2 h-2 bg-blue-brand rounded-full shadow-[0_0_10px_rgba(0,68,129,0.5)]"></div>
                </div>
            </div>

            <!-- Mensaje -->
            <div class="mb-8 text-center">
                <p class="text-zinc-600 font-light text-lg leading-relaxed">
                    Bienvenido al portal de gestión.<br>
                    <span class="text-sm text-zinc-500 font-medium">Identifícate para continuar.</span>
                </p>
            </div>

            <!-- Botones -->
            <div class="space-y-4 relative z-20">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="w-full flex justify-center items-center py-4 bg-blue-brand text-white rounded-lg hover:bg-blue-hover hover:scale-[1.02] transition shadow-lg font-medium tracking-wide text-sm">
                            Entrar al Panel
                        </a>
                    @else
                        <!-- Botón Principal -->
                        <a href="{{ route('login') }}"
                           class="group w-full flex justify-center items-center py-4 bg-blue-brand text-white rounded-lg hover:bg-blue-hover hover:scale-[1.02] transition shadow-lg font-medium tracking-wide text-sm">
                            <span>Iniciar Sesión</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M2121 12H3" />
                            </svg>
                        </a>

                        @if (Route::has('register'))
                            <!-- Botón Secundario -->
                            <a href="{{ route('register') }}"
                               class="w-full flex justify-center items-center py-4 bg-white/40 border border-blue-100 text-blue-900 rounded-lg hover:bg-white hover:border-blue-brand hover:text-blue-brand transition font-medium tracking-wide text-sm">
                                Solicitar Acceso
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center relative z-10">
            <p class="text-xs text-blue-900 opacity-60 font-medium tracking-wide">
                &copy; {{ date('Y') }} Cliché SGI
            </p>
        </div>
    </div>
</body>
</html>

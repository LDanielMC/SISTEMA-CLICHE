{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    {{-- ✅ Fondo estilo acuarela + tarjeta estilo landing (similar al home) --}}
    <div class="min-h-screen w-full overflow-hidden relative bg-white">
        {{-- Fondo Acuarela --}}
        <div class="fixed inset-0 z-0 overflow-hidden bg-white">
            <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-[#0ea5e9] blur-3xl opacity-70 animate-[float_12s_ease-in-out_infinite] mix-blend-normal"></div>
            <div class="absolute top-[-10%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-[#6366f1] blur-3xl opacity-70 animate-[float_12s_ease-in-out_infinite] [animation-delay:2s] mix-blend-normal"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-[70vw] h-[50vw] rounded-full bg-[#3b82f6] blur-3xl opacity-80 animate-[float_12s_ease-in-out_infinite] [animation-delay:4s] mix-blend-normal"></div>
        </div>

        {{-- Contenido --}}
        <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4">
            <div class="w-full max-w-md relative overflow-hidden rounded-3xl bg-white/60 backdrop-blur-md shadow-xl border border-white/50 p-10">
                {{-- Glow interno --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-[#004481] rounded-full blur-3xl opacity-10 pointer-events-none"></div>

                {{-- Branding --}}
                <div class="text-center mb-8">
                    <h2 class="font-serif text-4xl font-bold tracking-tight text-[#004481]">CLICHÉ</h2>
                    <div class="flex items-center justify-center gap-3 mt-3">
                        <span class="h-[1px] w-8 bg-[#004481] opacity-30"></span>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-blue-900 font-semibold">Iniciar sesión</p>
                        <span class="h-[1px] w-8 bg-[#004481] opacity-30"></span>
                    </div>
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-4 text-sm" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-blue-900" />
                        <x-text-input
                            id="email"
                            class="block mt-1 w-full rounded-xl border border-blue-100 bg-white/70 focus:bg-white focus:border-[#004481] focus:ring-[#004481]"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-blue-900" />
                        <x-text-input
                            id="password"
                            class="block mt-1 w-full rounded-xl border border-blue-100 bg-white/70 focus:bg-white focus:border-[#004481] focus:ring-[#004481]"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center gap-2">
                            <input
                                id="remember_me"
                                type="checkbox"
                                class="rounded border-blue-200 text-[#004481] shadow-sm focus:ring-[#004481]"
                                name="remember"
                            >
                            <span class="text-sm text-zinc-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-900/70 hover:text-blue-900 underline underline-offset-4"
                               href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    {{-- Botón --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="group w-full flex justify-center items-center py-3.5 bg-[#004481] text-white rounded-xl hover:bg-[#003366] transition shadow-lg font-medium tracking-wide text-sm"
                        >
                            <span>{{ __('Log in') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor"
                                 class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Footer mini --}}
                <div class="mt-8 text-center">
                    <p class="text-xs text-blue-900 opacity-60 font-medium tracking-wide">
                        &copy; {{ date('Y') }} Cliché SGI
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Animación float (por si tu Tailwind no trae keyframes) --}}
    <style>
        @keyframes float {
            0%   { transform: translate(0px, 0px) scale(1); }
            33%  { transform: translate(30px, -50px) scale(1.1); }
            66%  { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
</x-guest-layout>

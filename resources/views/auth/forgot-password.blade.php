{{-- resources/views/auth/forgot-password.blade.php --}}
<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-white flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            {{-- Card --}}
            <div class="relative overflow-hidden rounded-2xl border border-sky-100 bg-white/90 shadow-xl backdrop-blur">
                {{-- Top watercolor bar --}}
                <div class="h-28 bg-gradient-to-r from-sky-300 via-sky-200 to-cyan-200 relative">
                    <div class="absolute inset-0 opacity-40"
                        style="background:
                            radial-gradient(900px 120px at 20% 30%, rgba(255,255,255,.75), transparent 60%),
                            radial-gradient(700px 160px at 70% 60%, rgba(255,255,255,.55), transparent 60%),
                            radial-gradient(600px 140px at 40% 90%, rgba(255,255,255,.45), transparent 60%);">
                    </div>
                </div>

                {{-- Content --}}
                <div class="px-6 sm:px-8 pb-8">
                    {{-- Logo (overlay correcto) --}}
                    <div class="flex justify-center -mt-12 relative z-10">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white shadow-lg border border-sky-100 flex items-center justify-center overflow-hidden">
                            <img
                                src="/img/logo-cliche.png"
                                alt="Cliché Marketing Digital"
                                class="h-16 sm:h-20 w-auto object-contain"
                            />
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <h1 class="text-xl font-extrabold tracking-tight text-slate-900">
                            Recuperar contraseña
                        </h1>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                            Escribe tu correo y te enviaremos un enlace para restablecer tu contraseña.
                        </p>
                    </div>


                    {{-- Session Status --}}
                    <div class="mt-6">
                        @if (session('status'))
                            <div class="mb-4 text-sm font-medium text-green-600">
                                {{ __('Te enviamos el enlace para restablecer tu contraseña.') }}
                            </div>
                        @endif

                    </div>

                    <form method="POST" action="{{ route('password.email') }}" class="mt-2">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Correo')" class="text-slate-700 font-semibold" />

                            <div class="mt-2">
                                <x-text-input
                                    id="email"
                                    class="block w-full rounded-xl border-sky-100 focus:border-sky-400 focus:ring-sky-300 shadow-sm"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    placeholder="tu@correo.com"
                                />
                            </div>

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Button --}}
                        <div class="mt-6">
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-white
                                       bg-gradient-to-r from-sky-600 via-sky-500 to-cyan-500
                                       hover:from-sky-700 hover:via-sky-600 hover:to-cyan-600
                                       focus:outline-none focus:ring-2 focus:ring-sky-300 focus:ring-offset-2
                                       shadow-lg shadow-sky-200 transition"
                            >
                                Enviar enlace de recuperación
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 opacity-90">
                                    <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                                    <path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                                </svg>
                            </button>
                        </div>

                        {{-- Footer note --}}
                        <p class="mt-4 text-center text-xs text-slate-500">
                            ¿Recordaste tu contraseña?
                            <a href="{{ route('login') }}" class="font-semibold text-sky-600 hover:text-sky-700 underline underline-offset-4">
                                Inicia sesión
                            </a>
                        </p>
                    </form>
                </div>
            </div>

            {{-- Small brand line --}}
            <p class="mt-6 text-center text-xs text-slate-400">
                © {{ date('Y') }} Cliché Marketing Digital
            </p>
        </div>
    </div>
</x-guest-layout>

<x-guest-layout>
    {{-- Encabezado Estilo Cliché --}}
    <div class="mb-6 text-center">
        <h2 class="font-serif text-3xl font-bold text-[#004481]">
            Restablecer Contraseña
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Ingresa tu nueva contraseña para recuperar el acceso a tu cuenta.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" class="text-[#004481] font-semibold" />
            <x-text-input id="email" 
                          class="block mt-1 w-full border-gray-300 focus:border-[#004481] focus:ring-[#004481] rounded-md shadow-sm" 
                          type="email" 
                          name="email" 
                          :value="old('email', $request->email)" 
                          required autofocus autocomplete="username" />
            
            {{-- MODIFICACIÓN: Detectar 'passwords.token' y cambiarlo por mensaje amigable --}}
            @php
                $emailErrors = collect($errors->get('email'))->map(function($msg) {
                    return $msg === 'passwords.token' ? '⚠️ Este enlace ya expiró. Por favor solicita uno nuevo.' : $msg;
                })->all();
            @endphp
            <x-input-error :messages="$emailErrors" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Nueva Contraseña')" class="text-[#004481] font-semibold" />
            <x-text-input id="password" 
                          class="block mt-1 w-full border-gray-300 focus:border-[#004481] focus:ring-[#004481] rounded-md shadow-sm" 
                          type="password" 
                          name="password" 
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-[#004481] font-semibold" />

            <x-text-input id="password_confirmation" 
                          class="block mt-1 w-full border-gray-300 focus:border-[#004481] focus:ring-[#004481] rounded-md shadow-sm"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            {{-- Botón con Azul Corporativo Cliché --}}
            <x-primary-button class="bg-[#004481] hover:bg-[#003366] focus:bg-[#003366] active:bg-[#002a55] transition ease-in-out duration-150">
                {{ __('Restablecer Contraseña') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
@include('navBar')
@yield('navBar')

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nome --}}
        <div>
            <x-input-label for="name" :value="__('Nome completo')" />
            <x-text-input id="name" class="block mt-1 w-full"
                          type="text" name="name"
                          :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Username --}}
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full"
                          type="text" name="username"
                          :value="old('username')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email" name="email"
                          :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Tipo utente (persona / azienda) --}}
        <div class="mt-4">
            <x-input-label for="tipo_utente" :value="__('Tipo utente')" />
            <select id="tipo_utente" name="tipo_utente"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                <option value="persona" {{ old('tipo_utente', 'persona') == 'persona' ? 'selected' : '' }}>Persona</option>
                <option value="azienda" {{ old('tipo_utente') == 'azienda' ? 'selected' : '' }}>Azienda</option>
            </select>
            <x-input-error :messages="$errors->get('tipo_utente')" class="mt-2" />
        </div>

        
        {{-- Partita IVA (mostrata solo se azienda) --}}
        <div id="partita_iva_wrapper" class="mt-4" style="display: none;">
            <x-input-label for="partita_iva" :value="__('Partita IVA')" />
            <x-text-input id="partita_iva" class="block mt-1 w-full"
                        type="text" name="partita_iva"
                        :value="old('partita_iva')" autocomplete="off" />
            <x-input-error :messages="$errors->get('partita_iva')" class="mt-2" />
        </div>

        {{-- Codice fiscale --}}
        <div class="mt-4">
            <x-input-label for="codice_fiscale" :value="__('Codice fiscale')" />
            <x-text-input id="codice_fiscale" class="block mt-1 w-full"
                          type="text" name="codice_fiscale"
                          :value="old('codice_fiscale')" autocomplete="off" />
            <x-input-error :messages="$errors->get('codice_fiscale')" class="mt-2" />
        </div>

        {{-- Data di nascita --}}
     <div class="mt-4">
        <x-input-label for="data_di_nascita" :value="__('Data di nascita')" />
        
        <x-text-input id="data_di_nascita" class="block mt-1 w-full"
            type="date" name="data_di_nascita"
            :value="old('data_di_nascita')"
            min="1900-01-01"
            max="{{ now()->toDateString() }}" />

        <x-input-error :messages="$errors->get('data_di_nascita')" class="mt-2" />
    </div>



        {{-- Numero di telefono --}}
        <div class="mt-4">
            <x-input-label for="numero" :value="__('Numero di telefono')" />
            <x-text-input id="numero" class="block mt-1 w-full"
                          type="text" name="numero"
                          :value="old('numero')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('numero')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Conferma Password --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Conferma Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
        </div>

        {{-- Già registrato --}}
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('login') }}">
                {{ __('Sei già registrato?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrati') }}
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tipoUtente = document.getElementById('tipo_utente');
                const pivaWrapper = document.getElementById('partita_iva_wrapper');

                function togglePIVA() {
                    if (tipoUtente.value === 'azienda') {
                        pivaWrapper.style.display = 'block';
                    } else {
                        pivaWrapper.style.display = 'none';
                    }
                }

                tipoUtente.addEventListener('change', togglePIVA);
                togglePIVA();
            });
        </script>
    @endpush
</x-guest-layout>


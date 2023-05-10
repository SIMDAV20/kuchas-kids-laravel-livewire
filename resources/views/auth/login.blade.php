<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            {{-- <x-authentication-card-logo /> --}}
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <a href="{{ route('welcome') }}">
                <img src="{{ asset('img/logo.jpg') }}" class="h-34 w-full" alt="logo">
            </a>

            <h1 class="text-2xl text-center text-blue-700 font-bold my-4 uppercase">Iniciar Sesión</h1>

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />
            </div>

            <div class="flex justify-between items-center mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-blue-600">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="my-4">
                <x-button class="w-full cursor-pointer" color="blue">
                    {{ __('Log in') }}
                </x-button>
            </div>
            <div class="text-center">
                <span class="text-sm mb-3">
                    ¿Aún no tienes una cuenta?
                </span>
            </div>
            <div class="my-4">
                <x-button-enlace href="{{ route('register') }}" color="pink"
                    class="w-full hover:bg-white hover:text-pink-600 hover:font-extrabold">
                    CREA TU CUENTA
                </x-button-enlace>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

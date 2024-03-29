<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      {{-- <x-authentication-card-logo /> --}}
    </x-slot>

    <x-validation-errors class="mb-4" />

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <a href="{{ route('welcome') }}">
        <img src="{{ Storage::url($settings_company->logo) }}" class="h-34 w-full" alt="logo">
      </a>

      <h1 class="text-2xl text-center text-blue-700 font-bold my-4 uppercase">Crear Cuenta</h1>

      <div>
        <x-label for="name" value="{{ __('Name') }}" />
        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
          autofocus autocomplete="name" />
      </div>

      <div class="mt-4">
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
      </div>

      <div class="mt-4">
        <x-label for="password" value="{{ __('Password') }}" />
        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
          autocomplete="new-password" />
      </div>

      <div class="mt-4">
        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation"
          required autocomplete="new-password" />
      </div>

      @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
          <x-label for="terms">
            <div class="flex items-center">
              <x-checkbox name="terms" id="terms" />

              <div class="ml-2">
                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                    'terms_of_service' =>
                        '<a target="_blank" href="' .
                        route('terms.show') .
                        '" class="underline text-sm text-gray-600 hover:text-gray-900">' .
                        __('Terms of Service') .
                        '</a>',
                    'privacy_policy' =>
                        '<a target="_blank" href="' .
                        route('policy.show') .
                        '" class="underline text-sm text-gray-600 hover:text-gray-900">' .
                        __('Privacy Policy') .
                        '</a>',
                ]) !!}
              </div>
            </div>
          </x-label>
        </div>
      @endif

      <div class="my-4">
        <x-button class="w-full cursor-pointer" color="blue">
          {{ __('Register') }}
        </x-button>
      </div>
      <div class="my-4 text-center">
        <span class="text-sm text-blue-600 hover:text-blue-900">
          {{ __('Already registered?') }}
        </span>
      </div>
      <div>
        <x-button-enlace href="{{ route('login') }}" color="pink"
          class="w-full hover:bg-white hover:text-pink-600 hover:font-extrabold">
          INICIAR SESIÓN
        </x-button-enlace>
        {{-- <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button> --}}
      </div>
    </form>
  </x-authentication-card>
</x-guest-layout>

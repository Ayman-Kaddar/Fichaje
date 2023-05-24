<x-guest-layout>
    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
        style="background-image: url(img/verd.jpg); background-size: cover;">
        @push('styles')
            <link rel="stylesheet" href="{{ asset('css/login-register.css') }}">
        @endpush
        <x-jet-authentication-card>

            <x-slot name="logo">
                {{-- <x-jet-authentication-card-logo /> --}}
            </x-slot>

            <x-jet-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    <x-jet-label for="name" value="{{ __('Nom') }}" />
                    <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <x-jet-label for="email" value="{{ __('Email') }}" />
                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required />
                </div>

                <div class="mt-4">
                    <x-jet-label for="password" value="{{ __('Contraseña') }}" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-jet-label for="password_confirmation" value="{{ __('Contraseña') }}" />
                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-jet-label for="pin" value="{{ __('PIN') }}" />
                    <x-jet-input id="pin" class="block mt-1 w-full" type="text" name="pin" required
                        autocomplete="current-pin" pattern="[0-9]{4}"
                        title="Por favor, introduce un número de 4 dígitos" />
                </div>


                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4">
                        <x-jet-label for="terms">
                            <div class="flex items-center">
                                <x-jet-checkbox name="terms" id="terms" />

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
                        </x-jet-label>
                    </div>
                @endif

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Ja tens un compte?') }}
                    </a>

                    <x-jet-button class="ml-4">
                        {{ __('Crear Compte') }}
                    </x-jet-button>
                </div>
            </form>
        </x-jet-authentication-card>
</x-guest-layout>

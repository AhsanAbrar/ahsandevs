<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', 'admin@example.com')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            value="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3" onclick="fathom.trackEvent('Log in');">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        @php
            $users = [
                'Admin' => ['email' => 'admin@example.com', 'password' => 'password'],
                'User' => ['email' => 'user@example.com', 'password' => 'password'],
                // 'Manager' => ['email' => 'manager@example.com', 'password' => 'password'],
            ];
        @endphp

        <div class="mt-6 mb-2 border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($users as $user => $credentials)
                    <tr>
                        <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600 rounded-md">
                            {{ $user }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600 rounded-md">
                            <p>{{ $credentials['email'] }}</p>
                            <p>{{ $credentials['password'] }}</p>
                        </td>
                        <td class="w-8 whitespace-nowrap px-5 py-3 rounded-md">
                            <span class="block w-4 h-4" aria-label="Copy" data-cooltipz-dir="top" onclick="copy('{{ $user }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-gray-600 hover:text-gray-900">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                                </svg>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</x-guest-layout>

<script type="text/javascript">
    const users = @json($users)

    function copy(id) {
        const email = document.querySelector('#email')
        const password = document.querySelector('#password')
        email.value = users[id].email
        password.value = users[id].password
    }
</script>

<x-layouts.guest>
    <x-slot name="title">Sign In</x-slot>

    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-1">Welcome back</h2>
        <p class="text-sm text-gray-500 mb-6">Sign in with your UNILAK email</p>

        {{-- Session error (e.g. from rate-limit) --}}
        @if(session('error'))
            <div class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            {{-- Email --}}
            <x-input
                name="email"
                type="email"
                label="Email Address"
                placeholder="you@unilak.ac.rw"
                :required="true"
                autofocus
            />

            {{-- Password --}}
            <x-input
                name="password"
                type="password"
                label="Password"
                placeholder="Your password"
                :required="true"
            />

            {{-- Remember me --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-primary-800 focus:ring-primary-800"
                    />
                    Remember me
                </label>
                <a href="#" class="text-sm font-medium text-primary-800 hover:text-accent-500 transition">
                    Forgot password?
                </a>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <x-button type="submit" variant="primary" :full="true">
                    Sign In
                </x-button>
            </div>
        </form>

        {{-- Register link --}}
        <p class="mt-6 text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-primary-800 hover:text-accent-500 transition">
                Register now
            </a>
        </p>
    </div>
</x-layouts.guest>


<x-layouts.guest>
    <x-slot name="title">Create Account</x-slot>

    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-1">Create your account</h2>
        <p class="text-sm text-gray-500 mb-6">Join the UNILAK student community</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Full Name --}}
            <x-input
                name="name"
                label="Full Name"
                placeholder="e.g. Jean Pierre Uwimana"
                :required="true"
                autofocus
            />

            {{-- Student ID --}}
            <x-input
                name="student_id"
                label="Student ID"
                placeholder="e.g. 2021/BSC/CS/001"
                :required="true"
            />

            {{-- Email --}}
            <x-input
                name="email"
                type="email"
                label="Email Address"
                placeholder="you@unilak.ac.rw"
                :required="true"
            />

            {{-- Department --}}
            <div class="flex flex-col gap-1">
                <label for="department" class="text-sm font-medium text-gray-700">
                    Department <span class="text-red-500 ml-0.5">*</span>
                </label>
                <select
                    id="department"
                    name="department"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-800 focus:border-primary-800 transition {{ $errors->has('department') ? 'border-red-400 bg-red-50' : '' }}"
                >
                    <option value="" disabled {{ old('department') ? '' : 'selected' }}>Select your department…</option>
                    @foreach([
                        'Faculty of Computing & Information Sciences',
                        'Faculty of Economic Sciences & Management',
                        'Faculty of Law',
                        'Faculty of Environmental Studies',
                        'Faculty of Education',
                    ] as $dept)
                        <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
                @error('department')
                    <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <x-input
                name="password"
                type="password"
                label="Password"
                placeholder="Minimum 8 characters"
                :required="true"
            />

            {{-- Confirm Password --}}
            <x-input
                name="password_confirmation"
                type="password"
                label="Confirm Password"
                placeholder="Repeat your password"
                :required="true"
            />

            {{-- Submit --}}
            <div class="pt-2">
                <x-button type="submit" variant="primary" :full="true">
                    Create Account
                </x-button>
            </div>
        </form>

        {{-- Login link --}}
        <p class="mt-6 text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-primary-800 hover:text-accent-500 transition">
                Sign in
            </a>
        </p>
    </div>
</x-layouts.guest>


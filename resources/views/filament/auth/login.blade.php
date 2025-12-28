<x-filament-panels::page.simple>
    {{-- <div class="w-full"> --}}
    <!-- Custom Header dengan Logo dan Tagline -->
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-blue-800 mt-4">Welcome Back!</h2>
        <p class="text-gray-600 text-sm mt-1">Sign in to your admin dashboard</p>
    </div>

    <!-- Login Form -->
    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>

    <!-- Footer Text -->
    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500">
            © {{ date('Y') }} 69Dev. All rights reserved.
        </p>
    </div>
    {{-- </div> --}}

    <!-- Custom Background Pattern -->
    <style>
        .fi-simple-page {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        }

        .fi-simple-main {
            background: white;
            box-shadow: 0 20px 25px -5px rgba(147, 51, 234, 0.1), 0 10px 10px -5px rgba(147, 51, 234, 0.04);
        }
    </style>
</x-filament-panels::page.simple>

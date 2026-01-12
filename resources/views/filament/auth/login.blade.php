<x-filament-panels::page.simple>
    <div class="w-full">
        <!-- Custom Header dengan Logo dan Tagline -->
        <div class="mb-8 text-center">
            <p class="text-gray-600 text-sm">Your Lucky Tech Portal</p>
            <h2 class="text-2xl font-bold text-gray-800 mt-4">Welcome Back!</h2>
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
    </div>
</x-filament-panels::page.simple>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        Selamat datang di menu administrasi TDI
                    </h1>
                </div>
                {{-- <x-welcome /> --}}
            </div>
        </div>
    </div>
</x-app-layout>

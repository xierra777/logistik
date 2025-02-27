@section('title', 'Create')

<x-app-layout>
    <div class="p-6 text-dark-900 dark:text-gray-100">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Shipments') }}
            </h2>
        </x-slot>
        <livewire:create-shipments />
    </div>
</x-app-layout>
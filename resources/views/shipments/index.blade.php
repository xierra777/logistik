@section('title', 'Shipments')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shipments') }}
        </h2>
    </x-slot>
<!-- Spill Your Content here nigga -->

<livewire:shipmment/>

</x-app-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }} | @yield('title', '')</title>

    @include('include.style')
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-x-auto max-h-5 scrollbar-custom">
    <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
    @csrf
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->

        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-dark-900 dark:text-gray-100">
                            {{ $slot ?? 'No content available' }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include('include.script')
</body>
<footer>
    <!-- Footer content can go here -->
</footer>

</html>
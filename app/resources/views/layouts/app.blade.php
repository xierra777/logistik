<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }} | @yield('title', '')</title>

    @include('include.style')
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="font-sans antialiased overflow-x-auto max-h-3 max-h overflow-y-auto 
    [&::-webkit-scrollbar]:w-2
    [&::-webkit-scrollbar-track]:rounded-full
    [&::-webkit-scrollbar-track]:bg-gray-100
    [&::-webkit-scrollbar-thumb]:bg-gray-300
    [&::-webkit-scrollbar-thumb]:rounded-full
    dark:[&::-webkit-scrollbar-track]:bg-neutral-700
    dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @if(session('alert'))
    <div class="alert alert-warning">
        {{ session('alert') }}
    </div>
    @endif

    @csrf
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <livewire:layout.navigation />
        <p class="alert alert-warning" wire:offline>
            Whoops, your device has lost connection. The web page you are viewing is offline.
        </p>

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
                <div class="max-w-8xl mx-auto sm:px-4 lg:px-4">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            {{ $slot ?? 'No content available' }}
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
    @include('include.script')
    @stack('scripts')
    <div x-data>
        <button @click="swal.fire({text: 'masih under construction yaa :D tetap sabar',
        icon: 'warning',title:'SEMANGAT TEROSSS',
         
         })" class="fixed bottom-4 right-4 z-50 transition-transform duration-50">
            <p class="w-12 flex justify-center items-center h-12  bg-blue-500 rounded-full text-white text-sm shadow-lg transition-transform duration-300 cursor-pointer">
                <span class="absolute inline-flex h-full w-full duration-1000 rounded-full bg-sky-400 opacity-75"></span>
                <span class="relative inline-flex size-3 rounded-full bg-sky-500">
                    <i class="fa-solid fa-comment-dots"></i>
                </span>
            </p>
        </button>
    </div>


</body>
<footer>
    <!-- Footer content can go here -->
    <div class="text-center py-4 text-gray-500 text-sm">
        &copy; {{ date('Y') }} A . All rights reserved.
    </div>
</footer>

</html>
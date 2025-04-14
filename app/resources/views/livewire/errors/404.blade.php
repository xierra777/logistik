@section('title' ,' not found')
<x-app-layout>
    <div class="flex flex-col items-center justify-center max-w-md mx-auto p-6 text-dark-900 dark:text-gray-100">
        <img class="w-84 h-68 mb-4 opacity-75 dark:opacity-50" src="{{ asset ('images/nodata.svg')}}" alt="">
        <div class="text-center p-5">
            <h1>404 - Page Not Found</h1>
            <p>Oops! The page you're looking for doesn't exist.</p>
        </div>
        <a href="{{ url('dashboard') }}" class="py-4 px-9 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200"><b>Return Home</b></a>
    </div>

</x-app-layout>
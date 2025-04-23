<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>404 - Page Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>


<body>
    <div class="flex flex-col items-center justify-center max-w-md mx-auto p-6 text-dark-900 dark:text-gray-100 h-screen">
        <div class="text-center">
            <img class="w-84 h-68 mb-4 opacity-75 dark:opacity-50" src="{{ asset ('images/404.svg')}}" alt="">
            <div class="text-center p-5">
                <h1>404 - Page Not Found</h1>
                <p>Oops! Page yang anda cari tidak ada..</p>
            </div>
            <button onclick="window.history.back()" class="py-4 px-9 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200">🔙 Kembali</button>
        </div>
    </div>

</body>


</html>
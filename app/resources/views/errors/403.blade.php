<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>403 - Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center grid grid-cols-2 gap-4 p-6">
        <img class=" w-84 h-68 mb-4 opacity-75 dark:opacity-50" src="{{ asset ('images/warning.svg')}}" alt="">
        <div class="flex justify-center items-center p-5">
            <div class="text-center text-xl">
                <h1 class="text-6xl font-bold text-red-600">403</h1>
                <p class="text-xl mt-4 text-red-700">Akses Ditolak</p>
                <p class="text-gray-500">Kamu tidak punya izin untuk membuka halaman ini.</p>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="mt-6 col-span-2 inline-block bg-blue-600 text-white w-full px-4 py-2 rounded-md">Kembali</a>


    </div>
</body>

</html>
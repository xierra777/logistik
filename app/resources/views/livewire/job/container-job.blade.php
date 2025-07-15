@section('title', 'Container Job Details')

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Container ') }}
        </h2>
    </x-slot>
    <div class="overflow-x-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">

            <div class="flex flex-col border border border-gray-300 p-1 rounded-lg">
                <label for="containerNo" class="text-sm text-center rounded-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Container No</label>
                <p class="text-lg text-gray-900 text-center dark:text-white bg-gray-400 dark:bg-gray-700 rounded px-3 py-2">
                    {{ $jobContainer->containers['containerNo'] ?? '' }}
                </p>
            </div>
            <div class="flex flex-col border border border-gray-300 p-1 rounded-lg">
                <label for="containerNo" class="text-sm text-center rounded-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Container Type</label>
                <p class="text-lg text-gray-900 text-center dark:text-white bg-gray-400 dark:bg-gray-700 rounded px-3 py-2">
                    {{ $jobContainer->containers['containerType'] ?? '' }}
                </p>
            </div>
            <div class="flex flex-col border border border-gray-300 p-1 rounded-lg">
                <label for="containerNo" class="text-sm text-center rounded-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Container Seal No</label>
                <p class="text-lg text-gray-900 text-center dark:text-white bg-gray-400 dark:bg-gray-700 rounded px-3 py-2">
                    {{ $jobContainer->containers['containerSealNo'] ?? '' }}
                </p>
            </div>
            <div class="flex flex-col border border border-gray-300 p-1 rounded-lg">
                <label for="containerNo" class="text-sm text-center rounded-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">No Of Packages</label>
                <p class="text-lg text-gray-900 text-center dark:text-white bg-gray-400 dark:bg-gray-700 rounded px-3 py-2">
                    {{ $jobContainer->containers['noOfPackages'] ?? '' }} {{ $jobContainer->containers['typeOfPackages'] ?? '' }}

                </p>
            </div>
            <div class="flex flex-col border border border-gray-300 p-1 rounded-lg">
                <label for="containerNo" class="text-sm text-center rounded-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Gross Weight</label>
                <p class="text-lg text-center text-gray-900 dark:text-white bg-gray-400 dark:bg-gray-700 rounded px-3 py-2">
                    {{ $jobContainer->containers['grossWeight'] ?? '' }} {{ $jobContainer->containers['typeOfGrossWeight'] ?? '' }}

                </p>
            </div>
            <div class="flex flex-col border border border-gray-300 p-1 rounded-lg">
                <label for="containerNo" class="text-sm text-center rounded-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Net Of Weight</label>
                <p class="text-lg text-gray-900 text-center dark:text-white bg-gray-400 dark:bg-gray-700 rounded px-3 py-2">
                    {{ $jobContainer->containers['netOfWeight'] ?? '-' }} {{ $jobContainer->containers['typeNetOfWeight'] ?? '' }}
                </p>
            </div>
        </div>

    </div>

    <hr class="my-4 border-blue-200 dark:border-blue-700" />
    <div x-data>
        <button
            type="button"
            wire:loading.attr="disabled"
            class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg hover:from-red-600 hover:to-pink-700 transition-all duration-300 ease-in-out font-medium disabled:opacity-50 transform hover:scale-105 active:scale-95 min-w-[140px]"
            x-on:click="
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this action!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d8',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, Keep it',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteContainer(@js($jobContainer->id));
                }
            })
        ">
            <span wire:loading.remove wire:target="deleteContainer">
                <i class="fas fa-ban mr-2"></i>Konfirmasi Void
            </span>

            <span wire:loading wire:target="deleteContainer" class="flex items-center justify-center">
                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>
    </div>

    <div class="flex justify-end mt-4  p-4 rounded-lg">
        <a href="{{ route('viewJob', ['id' => $job->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded hover:scale-105 transition duration-200">
            Back
        </a>
    </div>
</div>
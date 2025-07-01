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
    <button type="button" onclick="if(confirm('Are you sure you want to delete this container?')) { @this.deleteContainer({{ $jobContainer->id }}) }" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
        Delete Container
    </button>
    <div class="flex justify-end mt-4  p-4 rounded-lg">
        <a href="{{ route('viewJob', ['id' => $job->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded hover:scale-105 transition duration-200">
            Back
        </a>
    </div>
</div>
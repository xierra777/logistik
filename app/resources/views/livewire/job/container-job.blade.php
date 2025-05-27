@section('title', 'Container Job Details')

<div>
    <div class="overflow-x-auto">
        <table class="table-hover min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-center">
            <thead>
                <tr>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Container No / Acitivity
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Gross Weight
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Volume
                    </th>
                </tr>
            </thead>
            <tbody class="">
                <tr>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$jobContainer->containers['containerNo'] ?? ''}}
                    </td>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$jobContainer->containers['grossWeight'] ?? ''}} {{$jobContainer->containers['typeOfGrossWeight'] ?? ''}}
                    </td>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$jobContainer->containers['Volume'] ?? ''}}
                        <p class="font-bold">CBM </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr class="my-4 border-blue-200 dark:border-blue-700" />
    <div class="flex justify-end mt-4  p-4 rounded-lg">
        <a href="{{ route('viewJob', ['id' => $job->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded hover:scale-105 transition duration-200">
            Back
        </a>
    </div>
</div>
@section('title', 'Accounting')

<div class="p-6 text-dark-900 dark:text-gray-100 space-y-5">
    <div class="grid grid-cols-3 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{route('chartOfAccount')}}">

                <h1 class="text-2xl font-bold mb-4">Chart Of Account</h1>
                <div class="flex items-center space-x-4">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">{{$coa}}</span> Accounts
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{url('/journal-entries')}}">

                <h1 class="text-2xl font-bold mb-4">Jurnal Laporan Keuangan</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">{{$shipmentWithTransactionsCount}} </span> Shipments</p>
                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">{{$totaltransaksi}}</span> Transactions
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@section('title', 'Accounting')

<div class="p-6 text-dark-900 dark:text-gray-100 space-y-5">
    <div class="grid grid-cols-4 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{url('/chart-of-accounts')}}">

                <h1 class="text-2xl font-bold mb-4">Chart Of Account</h1>
                <div class="flex items-center space-x-4">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 text-right"></p>
                        <span class="text-2xl font-bold">{{$coa}}</span> Accounts
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
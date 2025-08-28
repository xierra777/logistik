@section('title', 'Accounting')

<div class="p-6 text-dark-900 dark:text-gray-100 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{route('chartOfAccount')}}">

                <h1 class="text-2xl font-bold mb-4">Chart Of Account</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">

                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">{{$coa}}</span> Account
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{route('coaSetting')}}">
                <h1 class="text-2xl font-bold mb-4">Chart Of Charge Account</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">

                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">{{$chargeCoa}}</span> Charge Account
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{url('/journal-entries')}}">

                <h1 class="text-2xl font-bold mb-4">Accounting Report</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">

                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">{{$totaltransaksi}}</span> Transactions
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{route('listTax')}}">

                <h1 class="text-2xl font-bold mb-4">Tax account</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">

                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">{{$tax}}</span> Tax Account
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{route('customerDebt')}}">

                <h1 class="text-2xl font-bold mb-4">Customer Debt</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">

                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">Rp. {{ number_format($totalOutstanding, 2, ',', '.') }}</span> Customer Debt
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{route('accountant.list')}}">

                <h1 class="text-2xl font-bold mb-4">Invoice List</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">
                        <p>
                            Rp. {{ number_format($extra, 2, ',', '.') }} </p>
                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold"></span> Total issued Invoice
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{route('paymentTrans')}}">

                <h1 class="text-2xl font-bold mb-4">Payment List</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">
                        <p>
                            Rp. </p>
                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold"></span> Total issued Invoice
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
        <div class="p-3">
            <div>
                <h1 class="text-center font-bold text-2xl">Percentage Profit</h1>
            </div>
            <div x-data="lineChart()" x-init="initChart()" class="h-120">
                <div id="lineRevenueChart"></div>
            </div>
        </div>
          <div class="p-4 bg-white dark:bg-gray-800 border-gray-200 border shadow-md rounded-lg flex flex-col items-center text-center">
            <a href="{{route('listBank')}}">

                <h1 class="text-2xl font-bold mb-4">Bank List</h1>
                <div class="flex items-center space-x-4 ">
                    <div class="h-32 W-32 flex items-center justify-center text-4xl text-blue-600 w-1/3">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-700 dark:text-gray-300 text-left font-bold"> <span class="text-2xl font-bold">Rp. {{ number_format($totalOutstanding, 2, ',', '.') }}</span> Customer Debt
                        </p>
                        <p class="text-justify leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus nihil magni distinctio ipsam beatae! Ipsum deserunt illo error nam ea! </p>

                    </div>
                </div>
            </a>
        </div>
    </div>
</div>


<script>
    function lineChart() {
        return {
            chart: null,
            initChart() {
                this.chart = new ApexCharts(document.querySelector("#lineRevenueChart"), {
                    chart: {
                        type: 'area',
                        height: 150,
                        toolbar: {
                            show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },

                    series: [{
                            name: 'Revenue',
                            data: revenueData
                        },
                        {
                            name: 'Expense',
                            data: expenseData
                        }
                    ],
                    xaxis: {
                        categories: categories,
                        type: 'category'
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }).format(val);
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                });

                this.chart.render();
            }
        }
    }
</script>

<script>
    let categories = @json($categories); // e.g. ['2025-06-18', '2025-06-19', ...]
    let revenueData = @json($revenues); // e.g. [400000, 200000, ...]
    let expenseData = @json($expenses); // e.g. [300000, 150000, ...]
</script>
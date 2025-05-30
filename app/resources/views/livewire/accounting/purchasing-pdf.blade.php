<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="hhttps://cdn.tailwindcss.com">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Great+Vibes&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="">
    <div class="max-w-7xl border-collapse mx-auto bg-white shadow-lg  border border-gray-900 ">
        <div class="grid grid-cols-4 border border-seperate border-gray-900 text-sm ">
            <div class="col-span-4 p-2 flex ">
                <img src="{{ public_path('images/BERNUSA.png') }}" alt="Logo" class="w-48 h-auto">
                <div class="w-full text-right p-2">
                    <p class="text-orange-300">PT. BERKAH NUSANTARA INTERNATIONAL</p>
                    <br>
                    <p class="font-semibold text-blue-900">Graha Casablanca</p>
                    <p class="font-semibold text-blue-900">Jl. Casablanca No. 45, Jakarta Selatan</p>
                    <p class="font-semibold text-blue-900">021 38825291</p>
                    <p class="font-semibold text-blue-900">Graha Casablanca</p>
                    <a class="font-semibold text-blue-900">info@bernusa.id</a>
                </div>
            </div>
            <div class="col-span-4 border h-1 bg-blue-900  border-blue-900 mt-2"></div>
            <div class="col-span-2 p-1">
                <div class="grid grid-cols-4">
                    <p class="font-semibold">Invoice No.</p>
                    <p class="col-span-3">: {{ $invoice_number }}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Client</p>
                    <p class="col-span-3">: {{ $customer->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="col-span-4 border h-1 bg-orange-300  border-orange-300"></div>
            <div class="col-span-2 items-center mt-1 p-1">
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">VAT/IRS No.</p>
                    <p class="">: - </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">MAWB/MBL No.</p>
                    <p class="col-span-3">: {{$shipment->job?->data['jobBillLadingNo']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Shipper</p>
                    <p class="col-span-3">: {{$shipment->shipper->name}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Consignee</p>
                    <p class="col-span-3">: {{$shipment->consignee->name}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">ETA/ETD</p>
                    <p class="col-span-3">: {{ $shipment->dataShipments['shipmentEstimearrival'] ? \Carbon\Carbon::parse($shipment->dataShipments['shipmentEstimearrival'])->format('d M Y') : '-' }}
                        /
                        {{ $shipment->dataShipments['shipmentEstimedelivery'] ? \Carbon\Carbon::parse($shipment->dataShipments['shipmentEstimedelivery'])->format('d M Y') : '-' }}
                    </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">HAWB/HBL No.</p>
                    <p class="col-span-3">: </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Total No. of Pcs</p>
                    <p class="col-span-3 uppercase font-bold">: {{$totalPcs}} {{$shipment->container->first()->containersData['shipmentTypeOfPackages'] ?? '' }}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Total G.Weight</p>
                    <p class="col-span-3">: {{$totalgw}} {{$shipment->container->first()->containersData['shipmentTypeOfGrossWeight'] }}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Total Volume</p>
                    <p class="col-span-3">: </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Total V.Weight</p>
                    <p class="col-span-3">: </p>
                </div>
            </div>
            <div class="col-span-2 items-center mt-1 p-1">
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Date</p>
                    <p class="">: {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                    </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Job No.</p>
                    <p class="col-span-3">: {{$shipment->job->job_id ?? ''}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Shipment No.</p>
                    <p class="col-span-3">: {{$shipment->shipment_id}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Place Of Receipt</p>
                    <p class="col-span-3">: {{$shipment->dataShipments['shipmentPlace_of_receipt']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Port Of Loading</p>
                    <p class="col-span-3">: {{$shipment->dataShipments['shipmentPort_of_loading']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Port Of Dischage</p>
                    <p class="col-span-3">: {{$shipment->dataShipments['shipmentPort_of_discharge']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Vessel/Voyage</p>
                    <p class="col-span-3">: {{$shipment->dataShipments['shipmentFlightVesselName']}} / {{$shipment->dataShipments['shipmentFlightVesselNo']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Reference No.</p>
                    <p class="col-span-3">: </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Currency</p>
                    <p class="col-span-3">: {{$finalCurrency}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold">Narration</p>
                    <p class="col-span-3">: (Liners)</p>
                </div>
            </div>
            <div class="col-span-4 border h-4 bg-blue-900  border-blue-900"></div>
            <div class="col-span-4">
                <!-- Table 1 -->
                <table class="w-full table-fixed border-collapse border border-gray-900">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-neutral-800">
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Container No.</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Type</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">No Of Pcs</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Pack Of Type</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">G. Weight</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Unit</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Volume</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">V. Weight</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">C. Weight</th>
                        </tr>
                    </thead>
                    <tbody class="align-top">
                        @foreach($shipment->container as $c)
                        <tr class="text-center">
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-left">{{ $c->jobContainer->containers['containerNo'] ?? '' }}</td>
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center">{{ $c->jobContainer->containers['containerType'] ?? '' }}</td>
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentNoOfPackages'] }}</td>
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentTypeOfPackages'] }}</td>
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentGrossWeight'] }}</td>
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentTypeOfGrossWeight'] }}</td>
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentVolume'] }}</td>
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentVolumeWeight'] }}</td>
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Divider -->
                <div class="col-span-4 border h-4 bg-blue-900 border-blue-900"></div>
                <!-- Table 2 -->
                <table class="w-full table-fixed border-collapse border border-gray-900">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-neutral-800">
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Charge</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Qty</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Currency</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Ex.Rate</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Amount/Qty</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Sale Amount</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">VAT</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">WHT</th>
                            <th class="px-4 py-3 border border-gray-900 text-xs font-bold text-gray-900 uppercase text-center dark:text-neutral-400 w-1/9">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="align-top">
                        @foreach ($transactions as $transaction)
                        <tr class="bg-white dark:bg-neutral-900 align-top">
                            <td class="px-1 whitespace-nowrap  border-r border-l border-gray-900 text-center">{{ $transaction->description }}</td>
                            <td class="px-4 border-r border-l border-gray-900 text-center">{{ $transaction->quantity }}</td>
                            <td class="px-4 border-r border-l border-gray-900 text-center">{{ $transaction->ccurrency }}</td>
                            <td class="px-4 border-r border-l border-gray-900 text-center">@if($transaction->ccurrency == 'USD')
                                {{ $transaction->crate }}
                                @endif
                            </td>
                            <td class="px-2 border-r border-l border-gray-900 ">
                                <div class="flex justify-between w-full">
                                    <span class="text-left">
                                        {{ $transaction->ccurrency == 'IDR' ? 'IDR' : $transaction->ccurrency }}
                                    </span>
                                    <span class="text-right">
                                        {{ $transaction->ccurrency == 'IDR'
                                    ? $transaction->camountidr
                                    : number_format($transaction->cfcyamount, 2, '.', ',') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 border-r border-l border-gray-900 text-center">
                                <div class="flex justify-between w-full">
                                    <span class="text-left">{{ $transaction->ccurrency }}</span>
                                    <span class="text-right">
                                        {{ $transaction->ccurrency == 'IDR'
                                            ? number_format($transaction->subtotal, 2, ',', '.')
                                            : number_format($transaction->subtotal, 2, '.', ',') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 border-r border-l border-gray-900 text-center">
                                @if (!is_null($transaction->ctaxable) || !is_null($transaction->cvatgstusd))
                                <div class="flex justify-between w-full">
                                    <span class="text-left">{{ $transaction->ccurrency }}</span>
                                    <span class="text-right">
                                        {{ $transaction->ccurrency == 'IDR'
                                            ? $transaction->ctaxableamount
                                            : $transaction->cvatgstusd }}
                                    </span>
                                </div>
                                @else
                                &nbsp; {{-- biar tetap ada spacing kalau kosong --}}
                                @endif
                            </td>
                            <td class=" px-4 border-r border-l border-gray-900 text-center">
                                @if (($transaction->cwhtaxamount ?? 0) > 0 || ($transaction->chwtaxrateusd ?? 0) > 0)
                                <div class="flex justify-between w-full">
                                    <span class="text-left">{{ $transaction->ccurrency }}</span>
                                    <span class="text-right">
                                        {{ $transaction->ccurrency == 'IDR'
                                    ? $transaction->cwhtaxamount
                                    : $transaction->chwtaxrateusd }}
                                    </span>
                                </div>
                                @else
                                &nbsp; {{-- agar cell tetap terisi dan layout stabil --}}
                                @endif
                            </td>
                            <td class="px-4 border-r border-l border-gray-900 text-center">
                                <div class="flex justify-between w-full">
                                    <span class="text-left">{{ $transaction->ccurrency }}</span>
                                    <span class="text-right">
                                        {{ number_format($transaction->total, 2, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="col-span-4 mt-3">
                <div class="grid grid-cols-4">
                    <div class="col-start-5 w-full">
                        <div class="grid grid-cols-5 gap-y-2 px-5 text-right">
                            <!-- Subtotal -->
                            <div class="col-span-3 font-semibold">Sub total :</div>
                            <div class="text-left">{{ $finalCurrency }}</div>
                            <div>{{ $formattedSummary['subtotal'] }}</div>

                            <!-- VAT -->
                            <div class="col-span-3 font-semibold">VAT :</div>
                            <div class="text-left">{{ $finalCurrency }}</div>
                            <div>{{ $formattedSummary['vat'] }}</div>

                            <!-- WHT -->
                            <div class="col-span-3 font-semibold">WHT :</div>
                            <div class="text-left">{{ $finalCurrency }}</div>
                            <div>{{ $formattedSummary['wht'] }}</div>

                            <!-- Total -->
                            <div class="col-span-3 font-semibold">Total :</div>
                            <div class="text-left">{{ $finalCurrency }}</div>
                            <div>{{ $formattedSummary['total'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-4 border h-4 bg-orange-400  border-orange-400"></div>
            <div class="col-span-2 p-2">
                <p class="font-bold">Bank Details</p>
                <div class="grid grid-cols-3">
                    <div class="grid grid-cols-3 col-span-2">
                        <p class=" col-span-1">Bank Name</p>
                        <p class="text-left col-span-2">: Bank Mandiri</p>
                        <p class=" col-span-1">Company Name</p>
                        <p class="text-left col-span-2">: PT Berkah Nusantara International</p>
                        <p class=" col-span-1">Bank Name</p>
                        <p class="text-left col-span-2">0060012831172(IDR)</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 mt-2">
                    <div class="grid grid-cols-3 col-span-2">
                        <p class=" col-span-1">Bank Name</p>
                        <p class="text-left col-span-2">: Bank Mandiri</p>
                        <p class=" col-span-1">Company Name</p>
                        <p class="text-left col-span-2">: PT Berkah Nusantara International</p>
                        <p class=" col-span-1">Bank Name</p>
                        <p class="text-left col-span-2">: 0060013100023(USD)</p>
                        <p class=" col-span-1">Swift Code</p>
                        <p class="text-left col-span-2">: BMRIIDJAXXX</p>
                    </div>
                </div>
                <p class="font-semibold italic mt-1"> Payment Term 30 Days After Invoice received</p>

            </div>
        </div>
    </div>
</body>

</html>
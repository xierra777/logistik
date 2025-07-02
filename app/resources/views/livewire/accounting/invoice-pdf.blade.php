<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice_number }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="hhttps://cdn.tailwindcss.com">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Great+Vibes&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .draft-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 12rem;
            color: rgba(255, 0, 0, 0.1);
            font-weight: bold;
            z-index: 10;
            pointer-events: none;
            user-select: none;
        }
    </style>
</head>

<body class="shadow-lg m-1 text-xs ">
    <div class="draft-watermark">DRAFT</div>

    <div class="a4-wrapper align-center border border-gray-900 shadow-lg">
        <div class="grid grid-cols-4 border border-seperate border-gray-900 ">
            <div class="col-span-4 p-2 flex ">
                <img src="{{ public_path('images/BERNUSA.png') }}" alt="Logo" class="w-48 h-auto">
                <div class="w-full text-right p-2 ">
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
            <div class="col-span-2 p-1 ">
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Invoice No.</p>
                    <p class="col-span-3 text-xs">: {{ $invoice_number }}</p>
                </div>
                <div class="grid grid-cols-4 text-xs ">
                    <p class="font-semibold">Client</p>
                    <p class="col-span-3 text-xs">: {{ $customer->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="col-span-4 border h-1 bg-orange-300  border-orange-300"></div>
            <div class="col-span-2 items-center mt-1 p-1 ">
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">VAT/IRS No.</p>
                    <p class="text-xs">: - </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">MAWB/MBL No.</p>
                    <p class="col-span-3 text-xs">: {{$shipment->job?->jobBillLadingNo}}</p>
                </div>
                <div class="grid grid-cols-4  ">
                    <p class="font-semibold text-xs">Shipper</p>
                    <p class="col-span-3 text-xs">: {{$shipment->shipper?->name}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Consignee</p>
                    <p class="col-span-3 text-xs">: {{$shipment->consignee?->name }}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">ETA/ETD</p>
                    <p class="col-span-3 text-xs">: {{ $shipment->dataShipments['shipmentEstimearrival'] ? \Carbon\Carbon::parse($shipment->dataShipments['shipmentEstimearrival'])->format('d M Y') : '-' }}
                        /
                        {{ $shipment->dataShipments['shipmentEstimedelivery'] ? \Carbon\Carbon::parse($shipment->dataShipments['shipmentEstimedelivery'])->format('d M Y') : '-' }}
                    </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">HAWB/HBL No.</p>
                    <p class="col-span-3 text-xs">: {{$shipment->job?->houseJobBillLadingNo}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Total No. of Pcs</p>
                    <p class="col-span-3 uppercase font-bold text-xs">: {{$totalPcs}} {{$shipment->container->first()->containersData['shipmentTypeOfPackages'] }}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Total G.Weight</p>
                    <p class="col-span-3 text-xs">: {{$totalgw}} {{$shipment->container->first()->containersData['shipmentTypeOfGrossWeight'] }}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Total Volume</p>
                    <p class="col-span-3 text-xs">: </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Total V.Weight</p>
                    <p class="col-span-3 text-xs">: </p>
                </div>
            </div>
            <div class="col-span-2 items-center mt-1 p-1">
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Date</p>
                    <p class="text-xs">: {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                    </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Job No.</p>
                    <p class="col-span-3 text-xs">: {{$shipment->job?->job_id}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Shipment No.</p>
                    <p class="col-span-3 text-xs">: {{$shipment->shipment_id}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Place Of Receipt</p>
                    <p class="col-span-3 text-xs nowrap-whitespace">: {{$shipment->dataShipments['shipmentPlace_of_receipt']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Port Of Loading</p>
                    <p class="col-span-3 text-xs">: {{$shipment->dataShipments['shipmentPort_of_loading']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Port Of Dischage</p>
                    <p class="col-span-3 text-xs">: {{$shipment->dataShipments['shipmentPort_of_discharge']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Vessel/Voyage</p>
                    <p class="col-span-3 text-xs">: {{$shipment->dataShipments['shipmentFlightVesselName']}} / {{$shipment->dataShipments['shipmentFlightVesselNo']}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Reference No.</p>
                    <p class="col-span-3 text-xs">: </p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Currency</p>
                    <p class="col-span-3 text-xs">: {{$finalCurrency}}</p>
                </div>
                <div class="grid grid-cols-4 ">
                    <p class="font-semibold text-xs">Narration</p>
                    <p class="col-span-3 text-xs">: {{$shipment->carrierModel->name}}</p>
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
                    <tbody class="align-center">
                        @foreach($shipment->container as $c)
                        <tr class="text-center">
                            <td class="p-2 whitespace-nowrap  text-xs border-r border-l border-gray-900 text-left">{{ $c->jobContainer->containers['containerNo'] ?? '' }}</td>
                            <td class="px-1 whitespace-nowrap text-xs  border-r border-l border-gray-900 text-center">{{ $c->jobContainer->containers['containerType'] ?? '' }}</td>
                            <td class="px-1 whitespace-nowrap  text-xs border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentNoOfPackages'] }}</td>
                            <td class="px-1 whitespace-nowrap text-xs  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentTypeOfPackages'] }}</td>
                            <td class="px-1 whitespace-nowrap text-xs  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentGrossWeight'] }}</td>
                            <td class="px-1 whitespace-nowrap text-xs  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentTypeOfGrossWeight'] }}</td>
                            <td class="px-1 whitespace-nowrap text-xs  border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentVolume'] }}</td>
                            <td class="px-1 whitespace-nowrap text-xs border-r border-l border-gray-900 text-center">{{ $c->containersData['shipmentVolumeWeight'] }}</td>
                            <td class="px-1 whitespace-nowrap text-xs  border-r border-l border-gray-900 text-center"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Divider -->
                <div class="col-span-4 border h-4 bg-blue-900 border-blue-900"></div>
                <!-- Table 2 -->
                <table class="w-full table-fixed border-collapse border border-gray-900 divide-x text-xs">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-neutral-800 text-xs">
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
                    <tbody class="">
                        @foreach ($transactions as $transaction)
                        <tr class="bg-white dark:bg-neutral-900 align-top">
                            <td class="text-xs border border-gray-900 text-center">{{ $transaction->description }}</td>
                            <td class="px-4 text-xs border border-gray-900 text-center">{{ $transaction->quantity }}</td>
                            <td class="px-4 text-xs border border-gray-900 text-center">{{ $finalCurrency }}</td>
                            <td class="px-4 text-xs border border-gray-900 text-center">@if($showExchangeRate == 'USD')
                                {{ $transaction->srate }}
                                @endif
                            </td>
                            <td class="px-2 text-xs border border-gray-900 text-xs">
                                <div class="flex text-xs justify-between w-full">
                                    <span class="text-left text-xs">
                                        {{ $finalCurrency == 'IDR' ? 'IDR' : $transaction->scurrency }}
                                    </span>
                                    <span class="text-xs text-right">
                                        {{ $finalCurrency == 'USD'
                                    ? number_format($transaction->sfcyamount, 2, '.', ',')
                                    : number_format($transaction->samountidr, 2, '.', ',') }}
                                    </span>
                                </div>
                            </td>

                            </td>
                            <td class="px-2 border border-gray-900 text-xs">
                                <div class="flex justify-between w-full">
                                    <span class="text-left text-xs"> {{ $finalCurrency == 'IDR' ? 'IDR' : $transaction->scurrency }}
                                    </span>
                                    <span class="text-right text-xs">
                                        {{ $finalCurrency == 'IDR'
                                            ? number_format($transaction->subtotal, 2, ',', '.')
                                            : number_format($transaction->subtotal, 2, '.', ',') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 border border-gray-900 text-center text-xs">
                                @if (!is_null($transaction->svatgstamount) || !is_null($transaction->svatgstusd))
                                <div class="flex justify-between w-full">
                                    <span class="text-left text-xs">{{ $finalCurrency == 'IDR' ? 'IDR' : $transaction->scurrency }}</span>
                                    <span class="text-right text-xs">
                                        {{ $finalCurrency == 'IDR'
                                        ? number_format($transaction->svatgstamount, 2, ',', '.')
                                        : number_format($transaction->svatgstusd, 2, ',', '.') }}
                                    </span>
                                </div>
                                @else
                                &nbsp; {{-- biar tetap ada spacing kalau kosong --}}
                                @endif
                            </td>
                            <td class="px-2 border border-gray-900 text-center text-xs align-top">
                                @if (($transaction->swhtaxamount ?? 0) > 0 || ($transaction->shwtaxrateusd ?? 0) > 0)
                                <div class="flex justify-between w-full">
                                    <span class="text-left text-xs">{{ $finalCurrency == 'IDR' ? 'IDR' : $transaction->scurrency }}</span>
                                    <span class="text-right text-xs">
                                        {{ $finalCurrency == 'IDR'
                                    ? number_format($transaction->swhtaxamount, 2, ',', '.')
                                    : number_format($transaction->shwtaxrateusd, 2, ',', '.') }}
                                    </span>
                                </div>
                                @else
                                &nbsp; {{-- agar cell tetap terisi dan layout stabil --}}
                                @endif
                            </td>
                            <td class="px-2 border border-gray-900 text-center text-xs align-top">
                                <div class="flex justify-between w-full text-xs">
                                    <span class="text-left text-xs">{{ $finalCurrency == 'IDR' ? 'IDR' : $transaction->scurrency }}</span>
                                    <span class="text-right text-xs">
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
                        <div class="grid grid-cols-5 gap-y-2 px-5 text-right text-xs">
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
            <div class="col-span-2 p-2 mb-0">
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
                @if($customer->country == 'ID - Indonesia')
                <p class="font-semibold italic mt-1">Payment Term 30 Days After Invoice Received</p>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Bill of Lading</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Courier New';
        }
    </style>
</head>

<body class="font-mono">

    <div class="max-w-7xl mx-auto bg-white shadow-lg p-4">
        <!-- GRID UTAMA -->
        <div class="grid grid-cols-4 text-sm text-black  divide-x divide-y divide-gray-400">
            <!-- SHIPPER -->
            <div class="col-span-2 p-2 border-t border-l border-gray-400">
                <h2 class="font-bold uppercase ">Shipper</h2>
                <p>{{$shipment->shipper}}</p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Blanditiis deleniti facilis odio culpa quam, rem amet modi voluptatum tempore corporis.</p>
            </div>

            <!-- LOGO + TITLE + INFO -->
            <div class="col-span-2 row-span-4 flex flex-col items-center">

                <img src="{{ public_path('images/BERNUSA.png') }}" alt="Logo" class="h-40 w-full object-contain border-r border-b border-gray-400">
                <p class="text-lg text-blue-500 font-bold uppercase w-full text-center p-2 border-b border-r border-gray-400">
                    Bill of Lading
                </p>
                <div class="grid grid-cols-2 w-full border-b border-r border-gray-400">
                    <div class="p-2 border-r border-gray-400 text-center font-bold">
                        B/L Number<br><span class="font-normal">{{$shipment->shipment_no}}</span>
                    </div>
                    <div class="p-2 text-center font-bold">
                        S/O Number<br><span class="font-normal">(No S/O)</span>
                    </div>
                </div>
                <div class="w-full p-2 border-r border-gray-400">
                    <p class="text-xs pb-2">For Delivery Of Goods please apply to:</p>
                    <div class="h-40 w-full border border-dashed border-gray-400"></div>
                </div>
            </div>

            <!-- CONSIGNEE -->
            <div class="col-span-2 p-2">
                <h2 class="font-bold uppercase">Consignee</h2>
                <p>{{$shipment->consignee}}</p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Blanditiis deleniti facilis odio culpa quam, rem amet modi voluptatum tempore corporis.</p>
            </div>

            <!-- NOTIFY PARTY -->
            <div class="col-span-2 p-2">
                <h2 class="font-bold uppercase">Notify Party</h2>
                <p>{{$shipment->notify}}</p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Blanditiis deleniti facilis odio culpa quam, rem amet modi voluptatum tempore corporis.</p>
            </div>

            <!-- INFO 4 KOLOM -->
            <div class="col-span-2 grid grid-cols-2">
                <div class="p-2 border-r border-b border-gray-400">
                    <h2 class="font-bold uppercase text-sm">Pre-Carriage By</h2>
                    <p>{{$shipment->ocean_vessel_feeder}}</p>
                </div>
                <div class="p-2 border-b border-gray-400">
                    <h2 class="font-bold  uppercase text-sm">Place of Receipt</h2>
                    <p>{{$shipment->place_of_receipt}}</p>
                </div>
                <div class="p-2 border-r  border-gray-400">
                    <h2 class="font-bold text-sm">Export Carrier (Vessel Voyage)</h2>
                    <p>{{$shipment->ocean_vessel_mother}}</p>
                </div>
                <div class="p-2">
                    <h2 class="font-bold uppercase text-sm">Port of Loading</h2>
                    <p>{{$shipment->port_of_loading}}</p>
                </div>
            </div>
        </div>

        <!-- DETAIL PORT -->
        <div class="grid grid-cols-4 text-sm divide-x border-r border-t border-l border-gray-400 divide-gray-400">
            <div class="p-2">
                <h2 class="font-bold text-sm">Port Of Discharge</h2>
                <p>{{$shipment->port_of_discharge}}</p>
            </div>
            <div class="p-2">
                <h2 class="font-bold uppercase text-sm">Place Of Delivery</h2>
                <p>{{$shipment->port_of_discharge}}</p>
            </div>
            <div class="p-2">
                <h2 class="font-bold uppercase text-sm">Freight Payable at</h2>
                <p>(Detail)</p>
            </div>
            <div class="p-2">
                <h2 class="font-bold uppercase text-sm">Number of Original B/L</h2>
                <p>{{$shipment->shipment_no}}</p>
            </div>
        </div>

        <!-- HEADER TABEL -->
        <div class="grid grid-cols-7 text-sm text-center font-bold border border-gray-400 divide-x divide-gray-400">
            <div class="col-span-2 p-2">Marks & Numbers<br><span class="font-normal">Container & Seal Number</span></div>
            <div class="p-2">Number of Packages</div>
            <div class="col-span-2 p-2">Description of Packages and Goods<br><span class="text-sm font-normal">Particular Furnished by Shipper</span></div>
            <div class="p-2">Measurement<br>(cbm)</div>
            <div class="p-2">Gross Weight<br>(kilos)</div>
        </div>

        <!-- ISI TABEL -->
        <div class="grid grid-cols-7 h-96 text-sm border-l border-r border-gray-400 divide-x divide-gray-400">
            <div class="col-span-2 p-2">
                <p>CY/CY</p>
                <p>Dammam</p>
                <p class="text-center">// CONTAINER NO //</p>
                <p class="text-center">Container</p>
                <p class="text-center">Container</p>
                <p class="text-center">Container</p>
                <p class="text-center">Container</p>
            </div>
            <div class="p-2 text-right">
                <p>20GP X3</p>
                <br>
                <p>5,249 CARTONS</p>
            </div>
            <div class="col-span-2 p-2">
                <p>"SHIPPER'S LOAD, STOW, COUNT AND SEAL"</p>
                <p>SAID TO CONTAIN :</p>
                <br>
                <p>5249 CARTONS INDOFOOD CHILI SAUCE</p>
                <br>
                <p>ORDER NO:</p>
                <p>FS/121/PCL/SBTC/IX/2024-A-D</p>
                <p>FS/121/PCL/SBTC/IX/2024-A-D</p>
                <br>
                <p>NET WEIGHT : 42,432.68 KGS</p>
            </div>
            <div class="p-2">80.100 CBM</div>
            <div class="p-2 font-semibold text-lg">52,253.240 KGS</div>
        </div>

        <!-- FOOTER -->
        <div class="grid grid-cols-6 text-sm divide-y divide-x divide-gray-400">
            <table class="w-full col-span-4 table-fixed text-sm border-collapse  border-gray-400 h-full  ">
                <thead>
                    <tr>
                        <th class="border border-gray-400 p-2 font-bold text-left">Freight and Disbursements</th>
                        <th class="border border-gray-400 p-2 font-bold text-center">Revenue Tons</th>
                        <th class="border border-gray-400 p-2 font-bold text-center">Prepaid</th>
                        <th class="border-t border-b border-gray-400 p-2 font-bold text-center">Collect</th>
                    </tr>
                </thead>
                <tbody class="h-20">
                    <tr class="divide-x divide-gray-400">
                        <td class="border-l border-gray-400 p-2 align-top"></td>
                        <td class="p-2 align-top"></td>
                        <td class="p-2 align-top"></td>
                        <td class="p-2 align-top"></td>
                    </tr>
                </tbody>
            </table>

            <div class="col-span-2 p-2 row-span-4">
                <p class="text-xs">Lorem ipsum dolor sit amet consectetur adipisicing elit...</p>
                <p class="text-xs pt-11 text-center mt-36">AS AGENT OF THE CARRIER: PT.BERKAH NUSANTARA</p>
                <hr class="border-t border-gray-900 mt-2">
            </div>

            <div class="p-2 text-center font-bold">TOTAL :</div>
            <div></div>
            <div></div>
            <div></div>

            <div class="p-2 text-center text-xs font-bold">
                Place of B/L Issue <br>At Jakarta, Indonesia
                <div class="text-[10px] font-normal"></div>
            </div>
            <div class="p-2 text-center text-xs font-bold">
                Jakarta
                <div class="text-[10px] font-normal">Nov, 10.2024</div>
            </div>
            <div class="p-2 text-center text-xs font-bold">
                Indonesia
                <div class="text-[10px] font-normal">---</div>
            </div>
            <div class="p-2 text-center text-xs font-bold">
                On Board Date
                <div class="text-[10px] font-normal">NOV.10.2024</div>
            </div>

        </div>
    </div>
</body>

</html>
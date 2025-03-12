<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice_number }}</title>
    <!-- If you use Tailwind, make sure your compiled CSS is available -->
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .sigma {
            width: 100%;
            color: blue;
        }

        td,
        th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="text-center mb-6">
        <img src="{{ public_path('images/logo.png') }}" alt="Company Logo" width="150">
        <h2 class="text-2xl font-bold">PT. BERKAH NUSANTARA INTERNATIONAL</h2>
        <p>Graha Casablanca, Jl. Casablanca No. 45, Jakarta Selatan<br>
            021 22837847 | info@bernusa.id</p>
    </div>

    <!-- Invoice Header -->
    <table class="mb-6">
        <tr>
            <td><strong>Invoice No.:</strong> {{ $invoice_number }}</td>
            <td><strong>Client:</strong> {{ $customer->name }}</td>
        </tr>
        <tr>
            <td><strong>MAWB/MBL No.:</strong> {{ $shipment->shipment_id }}</td>
            <td><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Shipper:</strong> {{ $shipment->shipper }}</td>
            <td><strong>Due Date:</strong> {{ \Carbon\Carbon::now()->addDays(30)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Consignee:</strong> {{ $shipment->consignee }}</td>
            <td><strong>Currency:</strong> {{ $currency }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>ETA/ETD:</strong> {{ $shipment->eta_etd }}</td>
        </tr>
        <tr>
            <td><strong>Port of Loading:</strong> {{ $shipment->port_of_loading }}</td>
            <td><strong>Port of Discharge:</strong> {{ $shipment->port_of_discharge }}</td>
        </tr>
    </table>
    <div class="bg-blue-500 w-full h-5"></div>
    <div id="sigma" class="sigma"> ini divider</div>

    <!-- Container Details -->
    <h3 class="text-lg font-bold mb-2">Container Details</h3>
    <table class="mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th>Container No.</th>
                <th>Type</th>
                <th>No of Pcs</th>
                <th>G.Weight</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipment->containers as $container)
            <tr>
                <td>{{ $container->container_id }}</td>
                <td>{{ $container->container_type }}</td>
                <td>{{ $container->pack_type }}</td>
                <td class="text-right">{{ number_format($container->gross_weight, 2, ',', '.') }} KGS</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Transaction Details -->
    <h3 class="text-lg font-bold mb-2">Transaction Details</h3>
    <table class="mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th>Charge</th>
                <th>Qty</th>
                <th>Currency</th>
                <th>Amount</th>
                <th>VAT</th>
                <th>WHT</th>
                <th>Total</th>
            </tr>
        </thead>
        <div class="bg-blue-900 h-1 w-full"></div>

        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td class="p-2 border">{{ $transaction->description }}</td>
                <td class="p-2 border">{{ $transaction->quantity }}</td>
                <td class="p-2 border">{{ $transaction->scurrency }}</td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->samountidr))), 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->svatgstamount))), 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->swhtaxamount))), 2, ',', '.') }}
                </td>
                <td class="font-bold p-2 border">
                    {{ number_format(
                floatval(str_replace(',', '.', str_replace('.', '', $transaction->samountidr))) +
                floatval(str_replace(',', '.', str_replace('.', '', $transaction->svatgstamount))),
                2, ',', '.'
            ) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="3" class="p-2 border text-right">Total</td>
                <td class="p-2 border">
                    {{ number_format(
                $transactions->sum(fn($t) => floatval(str_replace(',', '.', str_replace('.', '', $t->samountidr)))),
                2, ',', '.'
            ) }}
                </td>
                <td class="p-2 border">
                    {{ number_format(
                $transactions->sum(fn($t) => floatval(str_replace(',', '.', str_replace('.', '', $t->svatgstamount)))),
                2, ',', '.'
            ) }}
                </td>
                <td class="p-2 border"></td>
                <td class="p-2 border">
                    {{ number_format(
                $transactions->sum(fn($t) => 
                    floatval(str_replace(',', '.', str_replace('.', '', $t->samountidr))) + 
                    floatval(str_replace(',', '.', str_replace('.', '', $t->svatgstamount)))
                ),
                2, ',', '.'
            ) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Bank Details -->
    <h3 class="text-lg font-bold mb-2">Bank Details</h3>
    <table>
        <tr>
            <td><strong>Bank Name:</strong> Bank Mandiri</td>
            <td><strong>Company Name:</strong> PT. Berkah Nusantara International</td>
        </tr>
        <tr>
            <td><strong>Account No:</strong> 0060012831172 (IDR)</td>
            <td><strong>Account No:</strong> 0060013100023 (USD)</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Swift Code:</strong> BMRIIDJAXXX</td>
        </tr>
    </table>
</body>

</html>
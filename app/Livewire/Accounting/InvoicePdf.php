<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use App\Models\Shipment;

class InvoicePdf extends Component
{
    public $invoiceId;

    public function generatePDF()
    {
        $invoice = Invoice::with(['customer', 'transactions', 'shipment.containers'])->findOrFail($this->customer_id);

        $pdf = Pdf::loadView('livewire.accounting.invoice-pdf-template', compact('invoice'))
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'invoice-' . $invoice->invoice_number . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.accounting.invoice-pdf');
    }
}

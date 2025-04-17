<?php

namespace App\Livewire;

use App\Models\Shipment;
use App\Models\Customer;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Livewire\Component;

class HouseBL extends Component
{
    public $pdfData = '';
    public $shipmentId;
    public $shipment;
    public $shipment_no;
    public function mount($shipmentId)
    {
        $this->shipmentId = $shipmentId;
        $this->shipment = Shipment::findOrFail($shipmentId);
    }


    public function generateHBL()
    {
        // if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
        //     session()->flash('error', 'Pilih shipment dan customer yang valid terlebih dahulu.');
        //     return;
        // }

        // Get Shipment and Customer
        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);

        // Prepare data for the view
        $data = compact('shipment');
        // Get current date (Optional - can be used for the document or logs)
        $now = Carbon::now();
        $formattedDate = $now->format('d-m-Y');  // For example: "24-03-2025"

        // Render the HTML view
        $html = view('livewire.pdfhbl', $data)->render();

        // Generate PDF content using Browsershot
        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome') // Make sure this is correct
            ->format('A3')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();

        // Return PDF content as a download response
        return response()->streamDownload(
            fn() => print($pdfContent),
            "HBL-{$this->shipment->shipment_no}-$formattedDate.pdf"
        );
    }

    public function previewHBL()
    {
        // if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
        //     session()->flash('error', 'No data available for preview.');
        //     return;
        // }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);



        $data = compact('shipment');

        $html = view('livewire.pdfhbl', $data)->render();
        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome')
            ->format('A3')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();

        $this->pdfData = base64_encode($pdfContent);

        $this->dispatch('open-pdf-preview', pdf: 'data:application/pdf;base64,' . $this->pdfData);
    }
    public function render()
    {
        return view('livewire.house-b-l', [
            'shipment' => $this->shipment
        ]);
    }
}

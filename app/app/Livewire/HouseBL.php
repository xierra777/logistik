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
    public $customer;
    public $shipment;
    public $shipment_no;
    public $totalPcs = 0;
    public $totalgw = 0;
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
        // $totalType = $shipment->containers->groupBy('container_type')->map->count();
        $totalPcs = $shipment->containers->sum('pcs');
        $totalgw = $shipment->containers->sum('gross_weight');

        // Prepare data for the view
        $containers = $shipment->containers; // Retrieve containers from the shipment
        $data = compact('shipment', 'customer') +  [
            'shipment' => $shipment,
            'containers' => $containers,
            'totalPcs'       => $totalPcs,
            'totalgw'         => $totalgw,
        ];
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
        $description = $shipment->description;
        $words = str_word_count($description, 1);
        $descFirstPage = implode(' ', array_slice($words, 0, 40));
        $descSecondPage = implode(' ', array_slice($words, 40));
        $totalPcs   = $shipment->containers->sum('pcs');
        $totalgw    = $shipment->containers->sum('gross_weight');
        $groupedUnits = $shipment->containers
            ->groupBy('unit')
            ->map(function ($group) {
                return [
                    'unit' => $group->first()->unit,
                    'totalPcs' => $group->sum('pcs'),
                ];
            })->values();
        $data = [
            'shipment' => $shipment,
            'containers' => $shipment->containers,
            'groupedUnits' => $groupedUnits,
            'totalPcs' => $totalPcs,
            'totalgw' => $totalgw,
            'descFirstPage' => $descFirstPage,
            'descSecondPage' => $descSecondPage,

        ];
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

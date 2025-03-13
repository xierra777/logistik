<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Shipment;
use App\Exports\ShipmentExport; // Ensure this class exists in the specified namespace
use App\Imports\ShipmentImport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;  // Add this import
use Carbon\Carbon;



class Shipmment extends Component
{
    use WithPagination;
    use WithFileUploads; // Add this trait

    public $name;
    public $perPage = 5; // Default per-page value
    public $mySelected = [];
    public $file;
    public $start_date, $end_date;


    protected $queryString = [
        'perPage' => ['except' => 5], // Store perPage in URL
    ];


    // public function mount()
    // {
    //     $this->start_date = now()->startOfMonth()->format('Y-m-d');
    //     $this->end_date = now()->format('Y-m-d');
    // }

    public function render()
    {
        $query = Shipment::query();

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }

        $shipments = $query->latest()->paginate($this->perPage);

        return view('livewire.shipmment', compact('shipments'));
    }

    protected $rules = [
        'file' => 'required|mimes:xlsx,xls,csv'
    ];
    public function downloadExcel()
    {
        return Excel::download(new ShipmentExport($this->start_date, $this->end_date), 'shipments.xlsx');
    }


    public function importExcel()
    {
        $this->validate();

        try {
            Excel::import(new ShipmentImport, $this->file);
            $this->reset('file');
            session()->flash('message', 'Shipments imported successfully!');
            $this->resetPage(); // Reset pagination after import
        } catch (\Exception $e) {
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function confirmDelete($get_id)
    {
        try {
            Shipment::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }
}

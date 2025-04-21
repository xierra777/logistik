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
    public $search = '';
    public $searchcust = '';

    public $name;
    public $perPage = 5; // Default per-page value
    public $mySelected = [];
    public $file;
    public $start_date, $end_date;
    public $searchField   = 'shipment_id';  // default column
    public $searchTerm    = '';

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
        $query = Shipment::latest(); // orderBy created_at desc

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [
                $this->start_date,
                $this->end_date
            ]);
        }

        if ($this->searchTerm) {
            // dynamically pick the column
            $column = $this->searchField;

            // if you need special handling for relationships or different names,
            // you can map here: e.g. 
            // if ($column==='customer_name') { ... join or whereHas … }
            $query->where($column, 'like', '%' . $this->searchTerm . '%');
        }

        $shipments = $query->paginate($this->perPage);

        return view('livewire.shipmment', compact('shipments'));
    }

    protected $rules = [
        'file' => 'required|mimes:xlsx,xls,csv'
    ];
    public function downloadExcel()
    {
        $now      = now()->format('Ymd_His');
        $filename = "shipments-{$this->start_date}_{$this->end_date}_{$now}.xlsx";

        try {
            return Excel::download(
                new ShipmentExport($this->start_date, $this->end_date, $this->searchTerm, $this->searchField),
                $filename
            );
        } catch (\Throwable $e) {
            session()->flash('error', 'Export failed: ' . $e->getMessage());
            return back();
        }
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

<?php

namespace App\Livewire\Shipment;

use Livewire\WithPagination;
use App\Models\TShipments;
use Livewire\Component;

class ListShipment extends Component
{
    use WithPagination;
    public $perPage = 5;
    public $start_date, $end_date;
    public $searchField   = 'shipment_id';  // default column
    public $searchTerm    = '';

    public function confirmDelete($get_id)
    {
        try {
            TShipments::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }
    public function render()
    {
        $query = TShipments::with('client')->latest(); // orderBy created_at desc

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [
                $this->start_date,
                $this->end_date
            ]);
        }

        if ($this->searchTerm) {
            if ($this->searchField === 'client') {
                $query->whereHas('client', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%');
                });
            } else {
                $normalizedSearch = str_replace(' ', '_', strtolower($this->searchTerm));

                $query->whereRaw("LOWER(REPLACE(`{$this->searchField}`, '_', ' ')) LIKE ?", ['%' . strtolower($this->searchTerm) . '%']);
            }
        }
        $shipment = $query->paginate($this->perPage);

        return view('livewire.shipment.list-shipment', compact('shipment'));
    }
}

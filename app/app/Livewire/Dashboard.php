<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Shipment;
use App\Models\TJob;
use App\Models\Customer;

class Dashboard extends Component
{
    use WithPagination;

    public $perPage = 5;

    public function mount() {}

    public function render()
    {
        $shipments = Shipment::latest()->paginate($this->perPage);
        $customers = Customer::latest()->paginate($this->perPage);
        $jobs = TJob::latest()->paginate($this->perPage);

        return view('livewire.dashboard', compact('shipments', 'customers', 'jobs'));
    }
}

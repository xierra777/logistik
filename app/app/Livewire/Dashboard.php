<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\TShipments;
use App\Models\TJob;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;

class Dashboard extends Component
{
    use WithPagination;

    public $perPage = 5;

    public function mount() {}

    public function render()
    {
        $shipments = TShipments::with('shipper', 'consignee', 'notify')->latest()->paginate($this->perPage);
        $customers = Customer::select('id', 'name', 'roles', 'country')->latest()->paginate($this->perPage);
        $jobs = TJob::with('client')->latest()->paginate($this->perPage);
        $users = User::select('name', 'email',)->latest()->paginate($this->perPage);
        $invoices = Invoice::where('status', 'issued')->latest()->paginate($this->perPage);

        return view('livewire.dashboard', compact('shipments', 'customers', 'jobs', 'users', 'invoices'));
    }
}

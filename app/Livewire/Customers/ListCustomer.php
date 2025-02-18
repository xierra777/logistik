<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\customer;

class ListCustomer extends Component
{
    use WithPagination;
    public $perPage = 3;

    public function confirmDelete($get_id)
    {
        try {
            customer::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.customers.list-customer', [
            'customers' => Customer::latest()->paginate($this->perPage)
        ]);
    }
}


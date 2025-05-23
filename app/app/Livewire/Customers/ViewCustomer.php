<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\customerAddress;
use Livewire\Component;

class ViewCustomer extends Component
{
    public $customer;
    public $chartOfAccount;
    public $address;
    public $editINGaddress;
    public $editingAddressId;
    public $isEditing = false;
    public function mount($id)
    {
        $this->customer = Customer::with('addresses', 'chartOfAccount')->findOrFail($id);
    }

    public function editAddress($addressId)
    {
        $address = customerAddress::findOrFail($addressId);

        $this->editingAddressId = $address->id;
        $this->editINGaddress = $address->address;
        $this->isEditing = true;
    }
    public function updateCostumer()
    {
        $address = customerAddress::findOrFail($this->editingAddressId);
        $address->update([
            'address' => $this->editINGaddress,
        ]);

        $this->reset(['isEditing', 'editINGaddress', 'editingAddressId']);

        session()->flash('message', 'Address updated successfully!');
    }

    public function createAddress()
    {
        customerAddress::create([
            'address'      => $this->address,
            'customer_id'  => $this->customer->id,
        ]);

        $this->dispatch('close-create-container');
    }
    public function confirmDelete($get_id)
    {
        try {
            customerAddress::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.customers.view-customer');
    }
}

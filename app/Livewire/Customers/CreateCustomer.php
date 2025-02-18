<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;

class CreateCustomer extends Component
{
    public $name, $email, $contact, $address, $web;
    public $roles = [];
    public $country;



    protected $rules = [
        'name'    => 'required|min:3',
        'email'   => 'required|email|unique:customers,email',
        'roles'   => 'min:1|array',
        'contact' => 'required',
        'address' => 'required',
    ];
 
    public function save()
    {
        $this->validate();
        // dd($this->name, $this->email, $this->contact, $this->country, $this->address, $this->web, $this->roles);
        // dd($this->roles);  // This will dump the roles to check if they are being passed correctly.
        Customer::create([
            'name'    => $this->name,
            'email'   => $this->email,
            'contact' => $this->contact,
            'country' => $this->country,
            'address' => $this->address,
            'web'     => $this->web,
            'roles'   => $this->roles,
        ]);

        return redirect()->route('customers.list')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title
        
        ]);
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.customers.create-customer');
    }
    
}

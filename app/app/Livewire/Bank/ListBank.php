<?php

namespace App\Livewire\Bank;

use Livewire\Component;
use App\Models\Bank;

class ListBank extends Component
{
    public $banks;
    public function render()
    {
        $this->banks = Bank::with('customer')->get();
        return view('livewire.bank.list-bank');
    }
}

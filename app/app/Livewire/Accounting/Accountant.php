<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\ChartOfAccount;



class Accountant extends Component
{
    public $coa;

    public function mount()
    {
        $this->coa = ChartOfAccount::count();
    }
    public function render()
    {
        return view('livewire.accounting.accountant');
    }
}

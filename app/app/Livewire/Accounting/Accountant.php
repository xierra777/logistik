<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\ChartOfAccount;
use App\Models\Transaction;
use App\Models\Shipment;

class Accountant extends Component
{
    public $coa;
    public $totaltransaksi;
    public $shipmentWithTransactionsCount;

    public function mount()
    {
        $this->coa = ChartOfAccount::count();
        $this->shipmentWithTransactionsCount = Shipment::has('transactions')->count();
        $this->totaltransaksi = Transaction::count();
    }
    public function render()
    {
        return view('livewire.accounting.accountant');
    }
}

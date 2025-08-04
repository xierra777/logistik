<?php

namespace App\Livewire\Accounting\Payment;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\PaymentJobAllocations;
use App\Models\Transaction;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

use function Laravel\Prompts\select;

class PaymentTransaction extends Component
{   public $payment;
    public function render()
    {
        $this->payment = Payment::with('allocations.invoices')->get();
        return view('livewire.accounting.payment.payment-transaction');
    }
}

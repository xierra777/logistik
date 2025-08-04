<?php

namespace App\Livewire\Accounting\Payment;

use App\Models\Payment;
use Livewire\Component;

class ViewPayment extends Component
{
    public $payment;
    public function mount($payId)
    {
        $payment = Payment::with('allocations.invoices')->findOrFail($payId);
        $this->payment = $payment;
        foreach ($payment->allocations as $allocation) {
            $invoiceAmount = $allocation->invoices->total_amount ?? 0;
            $allocated = $allocation->amount_allocated ?? 0;
            $allocation->kurang = $invoiceAmount - $allocated; // Tambahkan properti dinamis
        }
        
    }
    public function render()
    {
        return view('livewire.accounting.payment.view-payment');
    }
}

<?php

namespace App\Livewire\Accounting\Payment;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Transaction;
use Livewire\Component;

use function Laravel\Prompts\select;

class PaymentTransaction extends Component
{
    public $customers;
    public function mount()
    {
        $ransaction = Transaction::with('shipment', 'job');
        $this->customers = Invoice::with('shipment.shipmentTransaction', 'job.jobTransactions')->get();
        // dd($this->customers->map(fn($c) => [
        //     'invoice_id' => $c->id,
        //     'job_id' => $c->id_job,
        //     'has_job' => $c->job !== null,
        // ]));
    }
    public function render()
    {
        return view('livewire.accounting.payment.payment-transaction');
    }
}

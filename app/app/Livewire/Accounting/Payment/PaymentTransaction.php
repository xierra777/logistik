<?php

namespace App\Livewire\Accounting\Payment;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Transaction;
use Livewire\Component;

use function Laravel\Prompts\select;

class PaymentTransaction extends Component
{
    public $invoices, $coa, $payment_no;
    public $is_invoice, $is_purchasing, $customerVendor_id, $amount;
    public function mount()
    {
        $transactionConditions = function ($q) {
            $q->where(function ($q) {
                $q->whereNotNull('sclient')
                    ->orWhereNotNull('cvendor');
            })->where(function ($q) {
                $q->where('samountidr', '>', 0)
                    ->orWhere('camountidr', '>', 0);
            });
        };

        $this->invoices = Invoice::with([
            'shipment.shipmentTransaction' => $transactionConditions,
            'job.jobTransactions' => $transactionConditions,
        ])->where('status', '!=', 'void')
            ->where(function ($query) use ($transactionConditions) {
                $query->whereHas('shipment.shipmentTransaction', $transactionConditions)
                    ->orWhereHas('job.jobTransactions', $transactionConditions);
            })
            ->distinct()
            ->get();
        $this->coa = ChartOfAccount::where('is_payment', true)
            ->get();
        // dd($this->customers->map(fn($c) => [
        //     'invoice_id' => $c->id,
        //     'job_id' => $c->shipment,
        //     'has_job' => $c->job !== null,
        // ]));
        $this->generateCodeNo();
    }


    // Kalo yang atas ga jalan, coba ini
    public function updatedCustomerVendorId($value)
    {
        // dd('hey camelCase');
        $invoices = Invoice::find($value);
        $this->amount = $invoices->total_amount;
    }
    public function generateCodeNo()
    {
        $count = Payment::count() + 1;
        $date = now()->format('ym');
        $prefix = 'PAY/' . 'BRN/' . $date . str_pad($count, 3, 0, STR_PAD_LEFT);

        $this->payment_no = $prefix;
    }
    public function render()
    {
        return view('livewire.accounting.payment.payment-transaction');
    }
}

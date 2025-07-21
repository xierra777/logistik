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
{
    public $invoices, $coa, $payment_no, $customerVendor_id, $selectedCustVendor = [], $invoiceForeach = [], $amount, $exchange_rate, $currency, $payment_date, $remarks, $bank_coa, $allocations = [];
    public $is_invoice, $is_purchasing, $payment;
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
        // $this->allocations = [];

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
    public function updatedSelectedCustVendor($value)
    {
        // dd('Multiple select value:', $value); // This will be an array
        if (is_array($value) && !empty($value)) {
            $invoices = Invoice::whereIn('id', $value)->with('client', 'job', 'shipment')->get();

            // Langsung assign Collection ke property
            $this->invoiceForeach = $invoices;
            $amount = $invoices->sum('total_amount');

            $this->amount = $amount;
        } else {
            $this->invoiceForeach = collect([]); // Empty collection
        }
    }

    // CONTROLLER/SERVICE METHOD - savePayment()
    public function savePayment()
    {
        try {
            // selectedCustVendor adalah array invoice IDs
            $invoices = Invoice::whereIn('id', $this->selectedCustVendor)->get();
            $referenceId = null;
            $referenceType = null;
            $payment = Payment::create([
                'customerVendor_id' => $this->customerVendor_id,
                'payment_no'        => $this->payment_no,
                'payment_date'      => $this->payment_date,
                'bank_coa'          => $this->bank_coa,
                'amount'            => $this->amount,
                'currency'          => $this->currency,
                'exchange_rate'     => $this->exchange_rate,
                'remarks'           => $this->remarks,
                'reference_id'      => $referenceId,
                'reference_type'    => $referenceType,
            ]);
            foreach ($invoices as $invoice) {
                $amount = $this->allocations[$invoice->id] ?? 0;
                if (is_numeric($amount) && $amount > 0) {
                    PaymentAllocation::create([
                        'payment_id'        => $payment->id,
                        'invoice_id'        => $invoice->id,
                        'job_id'            => $invoice->job_id,
                        'shipment_id'       => $invoice->shipment_id,
                        'amount_allocated'  => (float) $amount,
                        'currency'          => $invoice->currency ?? $this->currency,
                        'exchange_rate'     => $invoice->exchange_rate ?? $this->exchange_rate,
                        'remarks'           => null,
                    ]);
                }
            }

            $this->generateCodeNo();
            session()->flash('message', 'Payment saved successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save payment: ' . $e->getMessage());
            throw $e;
        }
        $this->dispatch('close-modal');
        $this->reset();
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
        $this->payment = Payment::all();
        return view('livewire.accounting.payment.payment-transaction');
    }
}

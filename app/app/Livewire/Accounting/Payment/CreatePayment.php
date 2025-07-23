<?php

namespace App\Livewire\Accounting\Payment;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePayment extends Component
{
    public $invoices, $coa, $payment_no, $customerVendor_id, $selectedCustVendor,  $invoiceForeach = [], $amount, $exchange_rate, $currency, $payment_date, $remarks, $bank_coa, $allocations = [];
    public $is_invoice, $is_purchasing, $payment, $customers;
    public array $selectedInvoiceId = [];
    public function mount()
    {
        // $transactionConditions = function ($q) {
        //     $q->where(function ($q) {
        //         $q->whereNotNull('sclient')
        //             ->orWhereNotNull('cvendor');
        //     })->where(function ($q) {
        //         $q->where('samountidr', '>', 0)
        //             ->orWhere('camountidr', '>', 0);
        //     });
        // };
        // $this->invoices = Invoice::with([
        //     'shipment.shipmentTransaction' => $transactionConditions,
        //     'job.jobTransactions' => $transactionConditions,
        // ])->where('status', '!=', 'void')
        //     ->where(function ($query) use ($transactionConditions) {
        //         $query->whereHas('shipment.shipmentTransaction', $transactionConditions)
        //             ->orWhereHas('job.jobTransactions', $transactionConditions);
        //     })
        //     ->distinct()
        //     ->get();

        $this->customers = Customer::with('invoices')
            ->whereHas('invoices', function ($query) {
                $query->where('status', '!=', 'void');
            })
            ->distinct()
            ->get();

        $this->coa = ChartOfAccount::where('is_payment', true)
            ->get();

        $this->generateCodeNo();
    }
    protected $rules = [
        'selectedInvoiceId' => 'required|exists:invoices,id',
        'payment_date' => 'required',
    ];
    // Kalo yang atas ga jalan, coba ini
    public function updatedSelectedCustVendor($value)
    {
        $invoices = Invoice::when(is_array($value), function ($query) use ($value) {
            return $query->whereIn('customer_id', $value)->where('status', '!=', 'void');
        }, function ($query) use ($value) {
            return $query->where('customer_id', $value)->where('status', '!=', 'void');
        })->with('client', 'job.jobTransactions', 'shipment.shipmentTransaction', 'paymentAllocations')->get();
        if ($invoices->count() > 0) {
            $firstInvoice = $invoices->first();

            dd([
                'model_class' => get_class($firstInvoice),
                'casts' => $firstInvoice->getCasts(), // Lihat casting yang aktif
                'total_amount' => $firstInvoice->total_amount,
                'total_amount_type' => gettype($firstInvoice->total_amount),
                'raw_attributes' => $firstInvoice->getRawOriginal('total_amount'), // Nilai asli dari DB
            ]);
        }
        $this->invoiceForeach = $invoices->map(function ($inv) {
            // Convert to float to ensure numeric values
            $totalAmount = (float) ($inv->total_amount ?? 0);
            $totalPaid = (float) $inv->paymentAllocations->sum('amount_allocated');

            $inv->kurang = $totalAmount - $totalPaid;
            $inv->status_text = $totalPaid >= $totalAmount ? 'Lunas' : 'Belum Lunas';
            $inv->paid = $totalPaid;
            dump([
                'total_amount' => $inv->total_amount,
                'total_amount_type' => gettype($inv->total_amount),
                'amount_allocated' => gettype($inv->paymentAllocations->sum('amount_allocated')),
                'sum_result' => $inv->paymentAllocations->sum('amount_allocated'),
                'sum_type' => gettype($inv->paymentAllocations->sum('amount_allocated'))
            ]);
            return $inv;
        });
        // Debug the data types

    }
    public function selectedInvoice($id)
    {
        if (in_array($id, $this->selectedInvoiceId)) {
            $this->selectedInvoiceId = array_filter($this->selectedInvoiceId, fn($v) => $v != $id);
        } else {
            $this->selectedInvoiceId[] = $id;
        }
    }



    public function savePayment()
    {
        // $selectedIds = $this->selectedInvoiceId;
        // $invoices = Invoice::whereIn('id', $selectedIds)->get();
        // $total = $invoices->sum('total_amount');
        // dd($selectedIds, $invoices, $total);

        $this->validate();
        try {
            $invoices = Invoice::whereIn('id', $this->selectedInvoiceId)->get();
            $this->customerVendor_id = $this->selectedCustVendor;
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
            // dd($invoices);
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
        $this->reset();
        return redirect()->route('paymentTrans')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
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
        return view('livewire.accounting.payment.create-payment');
    }
}

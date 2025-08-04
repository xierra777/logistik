<?php

namespace App\Livewire\Accounting\Payment;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        'payment_date' => 'required',
    ];
    // Kalo yang atas ga jalan, coba ini
    public function updatedSelectedCustVendor($value)
    {
        $this->invoiceForeach = Invoice::when(is_array($value), function ($query) use ($value) {
            return $query->whereIn('customer_id', $value)->where('status', '!=', 'void');
        }, function ($query) use ($value) {
            return $query->where('customer_id', $value)->where('status', '!=', 'void');
        })->with('client', 'job.jobTransactions', 'shipment.shipmentTransaction', 'paymentAllocations')->get();
        $currecies =$this->invoiceForeach->first()->currency ?? null;
        $this->currency =  $currecies;

//           $invoices = Invoice::when(is_array($value), function ($query) use ($value) {
//             return $query->whereIn('customer_id', $value)->where('status', '!=', 'void');
//         }, function ($query) use ($value) {
//             return $query->where('customer_id', $value)->where('status', '!=', 'void');
//         })->with('client', 'job.jobTransactions', 'shipment.shipmentTransaction', 'paymentAllocations')->get();

// $this->invoiceForeach = $invoices->filter(function($invoice){        $totalAllocation = $invoice->paymentAllocations->sum('amount_allocated');   
//          $outstanding = $invoice->total_amount - $totalAllocation;
//          return $outstanding > 0;
// })->values();
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
        $this->validate();

        // Validasi input dasar
        if (empty($this->selectedInvoiceId)) {
            session()->flash('error', 'Please select at least one invoice.');
            return;
        }

        DB::beginTransaction();

        try {
            // Ambil invoices dengan payment allocations
            $invoices = Invoice::whereIn('id', $this->selectedInvoiceId)
                ->where('status', '!=', 'void')
                ->with('paymentAllocations')
                ->get();

            // Validasi count invoices
            if ($invoices->count() !== count($this->selectedInvoiceId)) {
                $this->dispatch('swal:alert', [
                    'icon' => 'error',
                    'title' => 'Invalid Selection!',
                    'text' => 'Some selected invoices are not found or invalid.'
                ]);
                return;
            }

            // Validasi setiap invoice dan allocation
            $validAllocations = [];
            $totalAllocationAmount = 0;

            foreach ($invoices as $invoice) {
                $allocationAmount = $this->allocations[$invoice->id] ?? 0;

                // Skip jika tidak ada allocation untuk invoice ini
                if (!is_numeric($allocationAmount) || $allocationAmount <= 0) {
                    continue;
                }

                $allocationAmount = (float) $allocationAmount;

                // Hitung total yang sudah dibayar sebelumnya
                $totalPaid = (float) $invoice->paymentAllocations->sum('amount_allocated');
                $totalAmount = (float) ($invoice->total_amount ?? 0);
                $outstanding = $totalAmount - $totalPaid;

                // Cek apakah invoice sudah lunas
                if ($totalPaid >= $totalAmount) {
                    $this->dispatch('swal:alert', [
                        'icon' => 'warning',
                        'title' => 'Invoice Already Paid!',
                        'html' => "Invoice <strong>{$invoice->invoice_number}</strong> is already fully paid.<br><br>Outstanding amount: <strong>Rp " . number_format($outstanding, 2) . "</strong>",
                        'confirmButtonText' => 'OK'
                    ]);
                    return;
                }

                // Cek apakah allocation amount melebihi outstanding
                if ($allocationAmount > $outstanding) {
                    $this->dispatch('swal:alert', [
                        'icon' => 'error',
                        'title' => 'Allocation Exceeds Outstanding!',
                        'html' => "Allocation amount for invoice <strong>{$invoice->invoice_number}</strong><br><br>" .
                            "Allocation: <strong>Rp " . number_format($allocationAmount, 2) . "</strong><br>" .
                            "Outstanding: <strong>Rp " . number_format($outstanding, 2) . "</strong><br><br>" .
                            "Please adjust the allocation amount.",
                        'confirmButtonText' => 'OK'
                    ]);
                    return;
                }

                // Simpan allocation yang valid
                $validAllocations[] = [
                    'invoice' => $invoice,
                    'amount' => $allocationAmount
                ];

                $totalAllocationAmount += $allocationAmount;
            }

            // Validasi bahwa ada allocation yang valid
            if (empty($validAllocations)) {
                $this->dispatch('swal:alert', [
                    'icon' => 'warning',
                    'title' => 'No Valid Allocations!',
                    'text' => 'No valid payment allocations found. Please enter allocation amounts for selected invoices.',
                    'confirmButtonText' => 'OK'
                ]);
                return;
            }

            // Validasi total allocation tidak melebihi payment amount
            if ($totalAllocationAmount != $this->amount) {
                $difference = $totalAllocationAmount - $this->amount;

                if ($difference > 0) {
                    // Total alokasi lebih besar dari payment
                    $this->dispatch('swal:alert', [
                        'icon' => 'error',
                        'title' => 'Total Allocation Exceeds Payment!',
                        'html' => "Total allocation amount cannot exceed payment amount.<br><br>" .
                            "Total Allocation: <strong>Rp " . number_format($totalAllocationAmount, 2) . "</strong><br>" .
                            "Payment Amount: <strong>Rp " . number_format($this->amount, 2) . "</strong><br>" .
                            "Excess Amount: <strong>Rp " . number_format($difference, 2) . "</strong><br><br>" .
                            "Please reduce the allocation amounts.",
                        'confirmButtonText' => 'OK'
                    ]);
                } else {
                    // Total alokasi kurang dari payment
                    $shortage = abs($difference);
                    $this->dispatch('swal:alert', [
                        'icon' => 'warning',
                        'title' => 'Incomplete Allocation!',
                        'html' => "Total allocation amount is less than payment amount.<br><br>" .
                            "Total Allocation: <strong>Rp " . number_format($totalAllocationAmount, 2) . "</strong><br>" .
                            "Payment Amount: <strong>Rp " . number_format($this->amount, 2) . "</strong><br>" .
                            "Remaining Amount: <strong>Rp " . number_format($shortage, 2) . "</strong><br><br>" .
                            "Please allocate the remaining amount.",
                        'confirmButtonText' => 'OK'
                    ]);
                }
                return;
            }
            // Create payment record
            $payment = Payment::create([
                'customerVendor_id' => $this->selectedCustVendor,
                'payment_no'        => $this->payment_no,
                'date'              => $this->payment_date,
                'bank_coa'          => $this->bank_coa,
                'amount'            => $this->amount,
                'currency'          => $this->currency,
                'exchange_rate'     => $this->exchange_rate,
                'remarks'           => $this->remarks,
                'reference_id'      => null, // Fix: jangan simpan collection object
                'reference_type'    => null,
                'journal_posted_at' => null,
                'created_by'        => Auth::id(),
            ]);
// dd($payment);
            // Create payment allocations
            foreach ($validAllocations as $allocation) {
                $invoice = $allocation['invoice'];
                $amount = $allocation['amount'];

                PaymentAllocation::create([
                    'payment_id'        => $payment->id,
                    'invoice_id'        => $invoice->id,
                    'job_id'            => $invoice->job_id,
                    'shipment_id'       => $invoice->shipment_id,
                    'amount_allocated'  => $amount,
                    'currency'          => $invoice->currency ?? $this->currency,
                    'exchange_rate'     => $invoice->exchange_rate ?? $this->exchange_rate,
                    'remarks'           => null,
                    'created_by'        => Auth::id(),
                ]);
            }

            DB::commit();

            $this->generateCodeNo();

            // Reset form fields
            $this->reset([
                'selectedInvoiceId',
                'allocations',
                'payment_no',
                'amount',
                'remarks',
                'invoiceForeach'
            ]);

            session()->flash('message', 'Payment saved successfully! ' . count($validAllocations) . ' invoice(s) allocated.');

            return redirect()->route('paymentTrans')->with('success', [
                'icon' => 'success',
                'title' => 'Payment Saved!',
                'message' => "Payment {$payment->payment_no} has been saved successfully."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error
            // \Log::error('Payment save failed: ' . $e->getMessage(), [
            //     'user_id' => Auth::id(),
            //     'selected_invoices' => $this->selectedInvoiceId ?? [],
            //     'allocations' => $this->allocations ?? []
            // ]);

            session()->flash('error', $e->getMessage());
            return;
        }
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

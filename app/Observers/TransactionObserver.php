<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Customer;

class TransactionObserver
{
    /**
     * Handle the Transaction "creating" event.
     */
    public function creating(Transaction $transaction)
    {
        // Jika customer_id belum diisi dan sclient ada, lakukan pencarian otomatis
        if (empty($transaction->customer_id) && !empty($transaction->sclient)) {
            $clientName = trim($transaction->sclient);
            // Cari customer dengan nama yang sama, ignore case
            $customer = Customer::whereRaw('LOWER(name) = ?', [strtolower($clientName)])->first();
            if ($customer) {
                $transaction->customer_id = $customer->id;
            }
        }
        if (empty($transaction->vendor_id) && !empty($transaction->cvendor)) {
            $vendorName = trim($transaction->cvendor);
            $vendor = Customer::whereRaw('LOWER(name) = ?', [strtolower($vendorName)])->first();
            if ($vendor) {
                $transaction->vendor_id = $vendor->id;
            }
        }
    }
}

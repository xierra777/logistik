<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CreateShipments;
use App\Livewire\ViewShipments;
use App\Livewire\EditShipments;
use App\Livewire\Shipmment;
use App\Livewire\Accounting\Accountant;
use App\Livewire\Accounting\PurchaseInvoice;
use App\Livewire\Accounting\Tranksaksi;
use App\Livewire\Accounting\SaleInvoice;
use App\Livewire\ChartOfAccounts;
use App\Livewire\Users;
use App\Livewire\JournalEntries;
use App\Livewire\Customers\CreateCustomer;
use App\Livewire\Customers\EditCustomer;
use App\Livewire\Customers\ViewCustomer;
use App\Livewire\Customers\ListCustomer;
use Illuminate\Http\Request;



Route::view('/', 'welcome');
Route::view('preline', 'preline');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('shipments', 'shipments.index',)
    ->middleware(['auth', 'verified'])
    ->name('shipments');
Route::view('shipment', 'shipments.create',)
    ->middleware(['auth'])
    ->name('shipment');

Route::get('/chart-of-accounts', ChartOfAccounts::class)->middleware([
    'auth',
    'verified'
]);


Route::get('/journal-entries', JournalEntries::class)->middleware([
    'auth',
    'verified'
]);

Route::get('create-shipments', CreateShipments::class)->middleware([
    'auth',
    'verified'
]);

Route::get('shipmment', Shipmment::class)->middleware([
    'auth',
    'verified'
]);
Route::get('/view-shipments/{id}', ViewShipments::class)->middleware([
    'auth',
    'verified'
]);
Route::get('/edit-shipments/{id}', EditShipments::class)->middleware([
    'auth',
    'verified'
]);

Route::get('/view-customers/{id}', ViewCustomer::class)->middleware([
    'auth',
    'verified'
]);

Route::get('/edit-customers/{id}', EditCustomer::class)->middleware([
    'auth',
    'verified'
]);
Route::get('/customers/create', CreateCustomer::class)->middleware(['auth', 'verified'])->name('customers.create');

Route::get('/customers', ListCustomer::class)
    ->middleware(['auth', 'verified'])
    ->name('customers.list');

Route::get('users', Users::class)
    ->middleware(['auth', 'verified'])
    ->name('user.list');

Route::get('accountant', Accountant::class)
    ->middleware(['auth', 'verified'])
    ->name('accountant.list');

Route::get('/accounting/tranksaksi', Tranksaksi::class)
    ->middleware(['auth', 'verified'])
    ->name('Tranksaksi');

Route::get('/purchase-invoice/{shipmentId}', PurchaseInvoice::class)
    ->middleware(['auth', 'verified'])
    ->name('purchase-invoice');

Route::get('/sale-invoice/{shipmentId}', SaleInvoice::class)
    ->middleware(['auth', 'verified'])
    ->name('sale-invoice');
Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf-token');

Route::fallback(function () {
    return view('livewire/errors.404');
});
require __DIR__ . '/auth.php';

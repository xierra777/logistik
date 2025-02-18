<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CreateShipments;
use App\Livewire\ViewShipments;
use App\Livewire\EditShipments;
use App\Livewire\Shipmment;
use App\Livewire\users;
use App\Livewire\Customers\CreateCustomer;
use App\Livewire\Customers\ListCustomer;
use Illuminate\Http\Request;


Route::view('/', 'welcome');

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


Route::get('create-shipments', CreateShipments::class)->middleware([
    'auth', 'verified'
]);

Route::get('shipmment', Shipmment::class)->middleware([
    'auth', 'verified'
]);
Route::get('/view-shipments/{id}', ViewShipments::class)->middleware([
    'auth', 'verified'
]);
Route::get('/edit-shipments/{id}', EditShipments::class)->middleware([
    'auth', 'verified'
]);
Route::get('/customers/create', CreateCustomer::class)->middleware(['auth', 'verified'])->name('customers.create');

Route::get('/customers', ListCustomer::class)
    ->middleware(['auth', 'verified'])
    ->name('customers.list');

Route::get('/user', users::class)
    ->middleware(['auth', 'verified'])
    ->name('user.list');

Route::get('/csrf-token', function (Request $request) {
return response()->json(['csrf_token' => csrf_token()]);
    })->name('csrf-token');


require __DIR__.'/auth.php';

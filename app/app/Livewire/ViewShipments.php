<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;
use App\Models\Container;
use App\Models\Transaction;

class ViewShipments extends Component
{
    public $editingContainerId = null;
    public $editContainer = [];
    public $newContainer = [];
    public $shipment;
    public $shipmentId;
    public $transaction; // Your list of transactions
    public $transactionId; // Selected transaction to edit
    public $isEditing = false;
    public $refreshKey = null;

    protected $listeners = [
        'invoiceGenerated',
        'transactionSaved' => 'refreshShipment',
        'closeModal' => 'closeEdit',
        'confirmDelete' // ini penting
    ];


    public function editTransaction($transactionId)
    {
        $this->transactionId = $transactionId;
        $this->isEditing = true;
        $this->dispatch('isEditing', $transactionId);
    }
    public function closeEdit()
    {
        // Reset isEditing ke false, sehingga modal akan ditutup
        $this->isEditing = false;
        // Reset juga transactionId jika perlu
        $this->transactionId = null;
    }
    public function mount($id)
    {
        $this->shipmentId = $id;
        $this->shipment = Shipment::with(['transactions', 'containers'])->findOrFail($id);
        $this->refreshKey = now()->timestamp;
        if ($this->shipment->transactions->isEmpty()) {
            $this->transaction = new Transaction();
        } else {
            $this->transaction = $this->shipment->transactions->first();
        }
    }

    public function refreshTransaction()
    {
        $this->refreshKey = now()->timestamp; // atau Str::uuid() juga bisa
    }
    public function refreshShipment()
    {
        $this->shipment = Shipment::with(['transactions', 'containers', 'invoices'])
            ->findOrFail($this->shipment->id);

        $this->dispatch('transaction', 'refreshTransactionData', $this->shipment->transactions);
    }
    public function triggerDelete()
    {
        $this->dispatch('confirm-delete');
    }
    public function confirmDelete($get_id)
    {
        try {
            // Attempt to delete the transaction
            Transaction::destroy($get_id);
        } catch (\Exception $e) {
            // Handle error
            session()->flash('error', 'Error deleting transaction: ' . $e->getMessage());
        }
    }
    public $editingContainer = null, $editContainerData = [
        'container_id' => '',
        'container_type' => '',
        'container_seal' => '',
        'pack_type' => '',
        'gross_weight' => '',
        'measurement' => '',
        'pcs' => '',
        'unit' => '',
        'volume_weight' => '',
        'chargeable_weight' => '',
    ];

    public function editContainer($id)
    {
        $container = Container::findOrFail($id);
        $this->editingContainer = $id;
        $this->editContainerData = $container->toArray();
    }

    public function updateContainer()
    {
        $this->validate([
            'editContainerData.container_id' => 'required',
            'editContainerData.container_type' => 'required',
            'editContainerData.container_seal' => 'nullable',
            'editContainerData.pack_type' => 'nullable',
            'editContainerData.gross_weight' => 'nullable|numeric',
            'editContainerData.measurement' => 'nullable',
            'editContainerData.pcs' => 'nullable',
            'editContainerData.unit' => 'nullable',
            'editContainerData.volume_weight' => 'nullable',
            'editContainerData.chargeable_weight' => 'nullable',
        ]);

        Container::where('id', $this->editingContainer)->update($this->editContainerData);

        $this->editingContainer = null;
        $this->editContainerData = [];
        $this->refreshShipment(); // Tambahkan refresh setelah update

        $this->dispatch('swal', ['title' => 'Updated!', 'icon' => 'success']);
    }

    public function resetEditing()
    {
        $this->editingContainerId = null;
        $this->editContainer = [];
    }

    public function containerDelete($get_id)
    {
        Container::findOrFail($get_id)->delete();
        $this->refreshShipment(); // Perbarui data shipment setelah delete
        session()->flash('success', 'Container deleted successfully.');
    }
    public function deleteContainer()
    {
        dispatch('confirm-apus');
    }

    public function createContainer()
    {
        $this->validate([
            'newContainer.container_id'   => 'required|max:255|unique:containers,container_id',
            'newContainer.container_type' => 'required|max:255',
            'newContainer.container_seal' => 'required|max:255',
            'newContainer.gross_weight'   => 'required|max:255',
            'newContainer.pack_type'      => 'required|max:255',
            'newContainer.unit'    => 'required|max:255',
            'newContainer.pcs'    => 'required|max:255',
        ]);

        Container::create(array_merge(['shipment_id' => $this->shipmentId], $this->newContainer));

        $this->resetNewContainer();
        $this->refreshShipment(); // Tambahkan refresh setelah create
        session()->flash('success', 'Container created successfully.');
    }

    public function resetNewContainer()
    {
        $this->newContainer = [
            'container_id'   => '',
            'container_type' => '',
            'container_seal' => '',
            'gross_weight'   => '',
            'pack_type'      => '',
            'measurement'    => '',
            'volume_weight'    => '',
            'chargeable_weight'    => '',
            'pcs'    => '',
            'unit'    => '',
        ];
    }

    public function render()
    {

        return view('livewire.view-shipments', [
            'shipmentId' => $this->shipmentId, // Kirim ke blade
            'transaction' => $this->transaction,

        ]);
    }
}

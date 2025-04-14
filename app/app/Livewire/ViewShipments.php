<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;
use App\Models\Container;

class ViewShipments extends Component
{
    public $editingContainerId = null;
    public $editContainer = [];
    public $newContainer = [];
    public $shipment;
    public $shipmentId;

    public $isEditing = false;
    protected $listeners = ['invoiceGenerated', 'transactionSaved' => 'refreshShipment'];

    public function editTransaction()
    {
        $this->isEditing = true;
    }
    public function closeEdit()
    {
        $this->isEditing = false; // Tutup form edit
    }
    public function mount($id)
    {
        $this->shipmentId = $id;
        $this->shipment = Shipment::with(['transactions', 'containers'])->findOrFail($id);
    }

    public function refreshShipment()
    {
        $this->shipment = Shipment::with(['transactions', 'containers', 'invoices'])
            ->findOrFail($this->shipment->id);

        // Emit ke child component untuk memperbarui datanya
        $this->dispatch('transaction', 'refreshTransactionData', $this->shipment->transactions);
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

    public function deleteContainer($containerId)
    {
        Container::findOrFail($containerId)->delete();
        $this->refreshShipment(); // Perbarui data shipment setelah delete
        session()->flash('success', 'Container deleted successfully.');
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
        ]);
    }
}

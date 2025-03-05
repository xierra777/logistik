<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;
use App\Models\Container;

class ViewShipments extends Component
{
    public $shipment;
    public $editingContainerId = null;
    public $editContainer = [];
    public $newContainer = [];


    public $editingContainer = null, $editContainerData = [
        'container_id' => '',
        'container_type' => '',
        'container_seal' => '',
        'pack_type' => '',
        'gross_weight' => '',
        'measurement' => '',
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
        ]);

        Container::where('id', $this->editingContainer)->update($this->editContainerData);

        $this->editingContainer = null;
        $this->editContainerData = [];
        $this->dispatch('swal', ['title' => 'Updated!', 'icon' => 'success']);
    }
    public function mount($id)
    {
        $this->shipment = Shipment::with(['transactions', 'containers'])->findOrFail($id);
    }

    private function refreshShipment()
    {
        $this->shipment->load('containers');
    }

    public function resetEditing()
    {
        $this->editingContainerId = null;
        $this->editContainer = [];
    }

    public function deleteContainer($containerId)
    {
        Container::findOrFail($containerId)->delete();
        $this->refreshShipment();
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
            'newContainer.measurement'    => 'required|max:255',
        ]);

        Container::create(array_merge(['shipment_id' => $this->shipment->id], $this->newContainer));

        $this->resetNewContainer();
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
        ];
    }

    public function render()
    {
        return view('livewire.view-shipments');
    }
}

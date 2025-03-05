<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;
use App\Models\Container;

class ViewShipments extends Component
{
    public $shipment;

    // For editing an existing container
    public $editingContainerId = null;
    public $editContainerData = [
        'container_id'   => '',
        'container_type' => '',
        'container_seal' => '',
        'pack_type'      => '',
        'gross_weight'   => '',
        'measurement'    => '',
    ];

    // For creating a new container
    public $newContainer = [
        'container_id'   => '',
        'container_type' => '',
        'container_seal' => '',
        'gross_weight'   => '',
        'pack_type'      => '',
        'measurement'    => '',
    ];

    public function mount($id)
    {
        $this->shipment = Shipment::with(['transactions', 'containers'])->findOrFail($id);
    }

    // When "Edit" is clicked, load container data for editing
    public function editContainer($containerId)
    {
        $container = Container::findOrFail($containerId);
        $this->editingContainerId = $containerId;
        $this->editContainerData = $container->toArray();
    }

    public function updateContainer()
    {
        $this->validate([
            'editContainerData.container_id'   => 'required|max:255|unique:containers,container_id,' . $this->editingContainerId,
            'editContainerData.container_type' => 'required|max:255',
            'editContainerData.container_seal' => 'nullable|max:255',
            'editContainerData.pack_type'      => 'nullable|max:255',
            'editContainerData.gross_weight'   => 'nullable|max:255',
            'editContainerData.measurement'    => 'nullable|max:255',
        ]);

        Container::findOrFail($this->editingContainerId)->update($this->editContainerData);
        $this->editingContainerId = null;
        $this->editContainerData = [
            'container_id'   => '',
            'container_type' => '',
            'container_seal' => '',
            'pack_type'      => '',
            'gross_weight'   => '',
            'measurement'    => '',
        ];
        $this->shipment->load('containers');
        session()->flash('success', 'Container updated successfully.');
    }

    public function cancelEdit()
    {
        $this->editingContainerId = null;
        $this->editContainerData = [
            'container_id'   => '',
            'container_type' => '',
            'container_seal' => '',
            'pack_type'      => '',
            'gross_weight'   => '',
            'measurement'    => '',
        ];
    }

    public function deleteContainer($containerId)
    {
        Container::findOrFail($containerId)->delete();
        $this->shipment->load('containers');
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
        $this->shipment->load('containers');
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

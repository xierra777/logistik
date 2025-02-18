<?php

namespace App\Imports;

use App\Models\Shipments;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ShipmentImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Shipments([
            'shipment_id' => $row['bl'] ?? $row['b/l'] ?? null,
            'container_id' => $row['container_id'] ?? null,
            'container_type' => $row['container_type'] ?? null,
            'shipper' => $row['shipper'] ?? null,
            'consignee' => $row['consignee'] ?? null,
            // Add other fields as needed
        ]);
    }

    public function rules(): array
    {
        return [
            'bl' => 'required|string',
            'container_id' => 'required|string',
            'container_type' => 'required|string',
            'shipper' => 'required|string',
            'consignee' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'bl.required' => 'B/L number is required',
            'container_id.required' => 'Container ID is required',
            'shipper.required' => 'Shipper field is required',
            'consignee.required' => 'Consignee field is required',
        ];
    }
}
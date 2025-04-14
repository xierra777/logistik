<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipments;
use Database\Factories\ShipmentsFactory;
class ShipmentsSeeder extends Seeder
{
    public function run(): void
    {
        Shipments::factory(50)->create(); // Creates 50 fake shipments
    }
}

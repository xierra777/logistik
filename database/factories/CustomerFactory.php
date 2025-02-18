<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'shipment_id' => $this->faker->unique()->uuid(),
            'container_id' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{7}'),
            'container_type' => $this->faker->randomElement(['20GP', '40GP', '40HC', '45HC']),
            'shipper' => $this->faker->company(),
            'consignee' => $this->faker->company(),
            'notify' => $this->faker->company(),
            'ocean_vessel_feeder' => $this->faker->word(),
            'ocean_vessel_mother' => $this->faker->word(),
            'port_of_discharge' => $this->faker->city(),
            'combined_transport' => $this->faker->boolean() ? 'Yes' : 'No',
            'port_of_loading' => $this->faker->city(),
            'packages' => $this->faker->randomNumber(2) . ' packages',
            'description' => $this->faker->sentence(),
            'gross_weight' => $this->faker->randomFloat(2, 100, 10000) . ' KG',
            'measurement' => $this->faker->randomFloat(2, 1, 50) . ' CBM',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

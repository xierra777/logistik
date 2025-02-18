<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shipments;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipments>
 */
class shipmentsFactory extends Factory
{
    protected $model = Shipments::class;

    public function definition(): array
    {
        return [
            'shipment_id' => $this->faker->unique()->uuid,
            'container_id' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{7}'),
            'container_type' => $this->faker->randomElement(['20ft', '40ft', '45ft', 'Reefer']),
            'shipper' => $this->faker->company,
            'consignee' => $this->faker->company,
            'notify' => $this->faker->optional()->company,
            'ocean_vessel_feeder' => $this->faker->optional()->word,
            'ocean_vessel_mother' => $this->faker->optional()->word,
            'port_of_discharge' => $this->faker->city,
            'combined_transport' => $this->faker->optional()->word,
            'port_of_loading' => $this->faker->city,
            'packages' => $this->faker->randomDigitNotNull,
            'description' => $this->faker->sentence,
            'gross_weight' => $this->faker->randomFloat(2, 1000, 50000) . ' KG',
            'measurement' => $this->faker->randomFloat(2, 1, 100) . ' CBM',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

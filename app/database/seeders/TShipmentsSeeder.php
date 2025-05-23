<?php

namespace Database\Seeders;

use App\Models\TShipments;
use App\Models\Customer;
use App\Models\TJob;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TShipmentsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $customers = Customer::pluck('id')->toArray();
        $jobs = TJob::pluck('id')->toArray();

        foreach (range(1, 20) as $i) {
            TShipments::create([
                'shipmentsTypeJob'    => $faker->randomElement(['ocean_fcl_export', 'ocean_fcl_import', 'air_inbound', 'air_outbound']),
                'shipment_id'         => $faker->uuid,
                'shipmentClient_id'   => $faker->randomElement($customers),
                'shipmentShipper_id'  => $faker->randomElement($customers),
                'shipmentConsignee_id' => $faker->randomElement($customers),
                'shipmentNotify_id'   => $faker->randomElement($customers),
                'shipmentCarrierAirline'             => $faker->randomElement($customers),
                'dataShipments'       => [
                    'vessel' => $faker->word,
                    'weight' => $faker->randomFloat(2, 100, 10000),
                    'volume' => $faker->randomFloat(2, 1, 100),
                    'package' => $faker->numberBetween(1, 500),
                ],
            ]);
        }
    }
}

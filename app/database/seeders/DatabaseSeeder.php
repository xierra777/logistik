<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TShipmentsSeeder::class);

        User::factory()->create([
            'name' => 'Fauzan Munif Hillabi',
            'email' => 'info@bernusa.id',
            'password' => 'password',
        ]);
    }
}

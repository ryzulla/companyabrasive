<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'PT Aneka Triprakarsa Pratama', 'logo' => null, 'order' => 1],
            ['name' => 'PT Indonesia Steel Tube Works', 'logo' => null, 'order' => 2],
            ['name' => 'PT Mekar Armada Jaya', 'logo' => null, 'order' => 3],
            ['name' => 'PT Farrindo Nusantara Industri', 'logo' => null, 'order' => 4],
            ['name' => 'PT Dharma Medipro', 'logo' => null, 'order' => 5],
            ['name' => 'PT Prestasi Cahaya Metalindo', 'logo' => null, 'order' => 6],
        ];

        foreach ($clients as $data) {
            Client::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}

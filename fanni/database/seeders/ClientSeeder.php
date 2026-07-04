<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'PT Astra International',        'logo' => null, 'order' => 1],
            ['name' => 'PT Krakatau Steel',              'logo' => null, 'order' => 2],
            ['name' => 'PT Pertamina',                   'logo' => null, 'order' => 3],
            ['name' => 'PT Wijaya Karya',                'logo' => null, 'order' => 4],
            ['name' => 'PT PAL Indonesia',               'logo' => null, 'order' => 5],
            ['name' => 'PT Dirgantara Indonesia',        'logo' => null, 'order' => 6],
            ['name' => 'PT Pindad',                      'logo' => null, 'order' => 7],
            ['name' => 'PT Toyota Motor Manufacturing',  'logo' => null, 'order' => 8],
            ['name' => 'PT Holcim Indonesia',            'logo' => null, 'order' => 9],
            ['name' => 'PT Waskita Karya',               'logo' => null, 'order' => 10],
            ['name' => 'PT Pupuk Kaltim',                'logo' => null, 'order' => 11],
            ['name' => 'PT Freeport Indonesia',          'logo' => null, 'order' => 12],
        ];

        foreach ($clients as $data) {
            Client::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}

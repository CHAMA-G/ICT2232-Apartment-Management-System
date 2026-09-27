<?php

namespace Database\Seeders;

use App\Models\Flat;
use Illuminate\Database\Seeder;

class FlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flats = [
            ['flat_number' => 'A-101', 'block' => 'A', 'floor' => '1st Floor'],
            ['flat_number' => 'A-102', 'block' => 'A', 'floor' => '1st Floor'],
            ['flat_number' => 'A-205', 'block' => 'A', 'floor' => '2nd Floor'],
            ['flat_number' => 'B-201', 'block' => 'B', 'floor' => '2nd Floor'],
            ['flat_number' => 'B-302', 'block' => 'B', 'floor' => '3rd Floor'],
            ['flat_number' => 'B-305', 'block' => 'B', 'floor' => '3rd Floor'],
            ['flat_number' => 'C-101', 'block' => 'C', 'floor' => '1st Floor'],
            ['flat_number' => 'C-401', 'block' => 'C', 'floor' => '4th Floor'],
            ['flat_number' => 'C-402', 'block' => 'C', 'floor' => '4th Floor'],
            ['flat_number' => 'C-405', 'block' => 'C', 'floor' => '4th Floor'],
        ];

        foreach ($flats as $flat) {
            Flat::firstOrCreate(
                ['flat_number' => $flat['flat_number']],
                ['block' => $flat['block'], 'floor' => $flat['floor']]
            );
        }
    }
}

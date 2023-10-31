<?php

namespace Database\Seeders;

use App\Models\Vessel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VesselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vessel::create([
            'name' => 'Titanic',
            'IMO_number' => '5385044',
        ]);

        Vessel::create([
            'name' => 'Queen Mary',
            'IMO_number' => '9241061',
        ]);

        Vessel::create([
            'name' => 'The Black Pearl',
            'IMO_number' => '0109687',
        ]);

        Vessel::create([
            'name' => 'USS Enterprise',
            'IMO_number' => '00707362',
        ]);
    }
}

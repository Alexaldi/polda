<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Polda Jawa Barat', 'type' => 'polda'],
        ];

        foreach ($items as $item) {
            Institution::updateOrCreate(
                ['name' => $item['name']],
                ['type' => $item['type']]
            );
        }
    }
}
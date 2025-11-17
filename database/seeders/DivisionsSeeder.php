<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'level' => 'polda',
                'permissions' => json_encode([
                    'inspection' => true,
                    'investigation' => false,
                ]),
                'name' => 'Subbid Paminal',
                'type' => 'satker',
            ],
            [
                'level' => 'polda',
                'permissions' => json_encode([
                    'inspection' => false,
                    'investigation' => true,
                ]),
                'name' => 'Subbid Provos',
                'type' => 'satker',
            ],
            [
                'level' => 'polda',
                'permissions' => json_encode([
                    'inspection' => false,
                    'investigation' => true,
                ]),
                'name' => 'Subbid Wabprof',
                'type' => 'satker',
            ],
            [
                'level' => 'polres',
                'permissions' => json_encode([
                    'inspection' => true,
                    'investigation' => false,
                ]),
                'name' => 'Unit Paminal',
                'type' => 'satker',
            ],
            [
                'level' => 'polres',
                'permissions' => json_encode([
                    'inspection' => false,
                    'investigation' => true,
                ]),
                'name' => 'Unit Provos',
                'type' => 'satker',
            ],
            [
                'level' => 'Polda',
                'permissions' => json_encode([
                    'inspection' => false,
                    'investigation' => false,
                ]),
                'name' => 'Polda Jawa Barat',
                'type' => 'satwil',
                'childrens' => [
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polrestabes Bandung',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polresta Bandung',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polresta Bogor Kota',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Bogor',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Sukabumi Kota',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Sukabumi',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Cianjur',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Purwakarta',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Karawang',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Subang',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Cimahi',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Sumedang',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Garut',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Tasikmalaya Kota',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Tasikmalaya',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Ciamis',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Cirebon',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polresta Cirebon',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Indramayu',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Majalengka',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Kuningan',
                        'type' => 'satwil',
                    ],
                    [
                        'level' => 'Polres',
                        'permissions' => json_encode([
                            'inspection' => false,
                            'investigation' => false,
                        ]),
                        'name' => 'Polres Banjar',
                        'type' => 'satwil',
                    ],
                ],
            ],
        ];

        // truncate table
        Division::truncate();
        foreach ($datas as $data) {
            $parent = Division::create([
                'level' => $data['level'],
                'permissions' => $data['permissions'],
                'name' => $data['name'],
                'type' => $data['type'],
            ]);

            if (isset($data['childrens'])) {
                foreach ($data['childrens'] as $child) {
                    Division::create([
                        'parent_id' => $parent->id,
                        'level' => $child['level'],
                        'permissions' => $child['permissions'],
                        'name' => $child['name'],
                        'type' => $child['type'],
                    ]);
                }
            }
        }
    }
}
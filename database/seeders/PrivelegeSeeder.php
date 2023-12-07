<?php

namespace Database\Seeders;

use App\Models\Priveleges;
use Illuminate\Database\Seeder;

class PrivelegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $namespace = [
            'list',
            'create',
            'edit',
            'trash',
            'delete',
            'trashed',
            'restore'
        ];

        $data = [
            [
                'group' => 'MASTER DATA',
                'sub_modul' => [
                    'user'
                ]
            ],
            [
                'group' => 'ROLE MANAGEMENT',
                'sub_modul' => [
                    'role',
                    'priveleges'
                ]
            ]
        ];

        for ($i = 0; $i < count($data); $i++) {
            $group = $data[$i]['group'];
            $subModule = $data[$i]['sub_modul'];
            for ($x = 0; $x < count($subModule); $x++) {
                $count = 1;
                for ($y = 0; $y < count($namespace); $y++) {
                    $dataPrivileges = [
                        'module' => $group,
                        'sub_module' => strtoupper($subModule[$x]),
                        'module_name' => ucwords($namespace[$y] . ' ' . $subModule[$x]),
                        'namespace' => $subModule[$x] . '.' . $namespace[$y],
                        'ordering' => $count++
                    ];

                    Priveleges::create($dataPrivileges);
                }
            }
        }
    }
}

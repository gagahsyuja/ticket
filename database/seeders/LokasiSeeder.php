<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = [
            [
                'id' => 1,
                'nama_lokasi' => 'Stadion Utama'
            ],
            [
                'id' => 2,
                'nama_lokasi' => 'Galeri Seni Kota'
            ],
            [
                'id' => 3,
                'nama_lokasi' => 'Taman Kota'
            ],
            [
                'id' => 4,
                'nama_lokasi' => 'Rumah Saya'
            ],
            [
                'id' => 5,
                'aktif' => '0',
                'nama_lokasi' => 'harusnya gakeluar'
            ]
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}

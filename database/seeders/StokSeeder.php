<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Barang dari Supplier 1
            [
                'stok_id' => 1,
                'barang_id' => 1,
                'user_id' => 1,
                'supplier_id' => 1,
                'stok_tanggal' => '2024-09-01',
                'stok_jumlah' => 100,
            ],
            [
                'stok_id' => 2,
                'barang_id' => 2,
                'user_id' => 2,
                'supplier_id' => 1,
                'stok_tanggal' => '2024-09-02',
                'stok_jumlah' => 50,
            ],
            [
                'stok_id' => 3,
                'barang_id' => 3,
                'user_id' => 3,
                'supplier_id' => 1,
                'stok_tanggal' => '2024-09-03',
                'stok_jumlah' => 75,
            ],
            [
                'stok_id' => 4,
                'barang_id' => 4,
                'user_id' => 3,
                'supplier_id' => 1,
                'stok_tanggal' => '2024-09-04',
                'stok_jumlah' => 30,
            ],
            [
                'stok_id' => 5,
                'barang_id' => 5,
                'user_id' => 3,
                'supplier_id' => 1,
                'stok_tanggal' => '2024-09-05',
                'stok_jumlah' => 40,
            ],

            // Barang dari Supplier 2
            [
                'stok_id' => 6,
                'barang_id' => 6,
                'user_id' => 2,
                'supplier_id' => 2,
                'stok_tanggal' => '2024-09-01',
                'stok_jumlah' => 200,
            ],
            [
                'stok_id' => 7,
                'barang_id' => 7,
                'user_id' => 2,
                'supplier_id' => 2,
                'stok_tanggal' => '2024-09-02',
                'stok_jumlah' => 180,
            ],
            [
                'stok_id' => 8,
                'barang_id' => 8,
                'user_id' => 2,
                'supplier_id' => 2,
                'stok_tanggal' => '2024-09-03',
                'stok_jumlah' => 250,
            ],
            [
                'stok_id' => 9,
                'barang_id' => 9,
                'user_id' => 2,
                'supplier_id' => 2,
                'stok_tanggal' => '2024-09-04',
                'stok_jumlah' => 150,
            ],
            [
                'stok_id' => 10,
                'barang_id' => 10,
                'user_id' => 2,
                'supplier_id' => 2,
                'stok_tanggal' => '2024-09-05',
                'stok_jumlah' => 120,
            ],

            // Barang dari Supplier 3
            [
                'stok_id' => 11,
                'barang_id' => 11,
                'user_id' => 1,
                'supplier_id' => 3,
                'stok_tanggal' => '2024-09-01',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 12,
                'barang_id' => 12,
                'user_id' => 1,
                'supplier_id' => 3,
                'stok_tanggal' => '2024-09-02',
                'stok_jumlah' => 25,
            ],
            [
                'stok_id' => 13,
                'barang_id' => 13,
                'user_id' => 1,
                'supplier_id' => 3,
                'stok_tanggal' => '2024-09-03',
                'stok_jumlah' => 50,
            ],
            [
                'stok_id' => 14,
                'barang_id' => 14,
                'user_id' => 1,
                'supplier_id' => 3,
                'stok_tanggal' => '2024-09-04',
                'stok_jumlah' => 10,
            ],
            [
                'stok_id' => 15,
                'barang_id' => 15,
                'user_id' => 1,
                'supplier_id' => 3,
                'stok_tanggal' => '2024-09-05',
                'stok_jumlah' => 15,
            ],
        ];

        DB::table('t_stok')->insert($data);
    }
}
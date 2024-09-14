<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'PT. Sumber Makmur',
                'supplier_alamat' => 'Jl. Kembang Turi No. 45, Jakarta',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'CV. Bina Karya',
                'supplier_alamat' => 'Jl. Semanggi Barat No. 12, Surabaya',
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'Toko Mandiri Jaya',
                'supplier_alamat' => 'Jl. Senggani No. 78, Jakarta',
            ],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
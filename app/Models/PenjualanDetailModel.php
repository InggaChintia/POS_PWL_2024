<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanDetailModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_detail'; // Definisikan tabel
    protected $primaryKey = 'penjualan_detail_id'; // Primary key dari tabel

    protected $fillable = ['penjualan_id', 'barang_id', 'jumlah', 'harga'];

    // Relasi ke barang
    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    // Relasi ke penjualan
    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(PenjualanModel::class, 'penjualan_id', 'penjualan_id');
    }
}

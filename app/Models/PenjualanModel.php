<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan'; // Definisikan tabel
    protected $primaryKey = 'penjualan_id'; // Primary key dari tabel

    protected $fillable = ['customer_id', 'tanggal', 'total_harga'];

    // Relasi ke tabel detail penjualan
    public function penjualanDetail(): HasMany
    {
        return $this->hasMany(PenjualanDetailModel::class, 'penjualan_id', 'penjualan_id');
    }

    // Relasi ke customer (jika ada tabel customer)
    public function customer(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'customer_id', 'customer_id');
    }
}

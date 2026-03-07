<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanProdukHukum extends Model
{
    use HasFactory;

    protected $table = 'pengambilan_produk_hukums';
    
    protected $guarded = [];
    // Jika ingin lebih eksplisit:
    // protected $fillable = [
    //     'surat_masuk_id', 'tanggal_pengambilan', 'instansi_pengambil', 'nama_pengambil', 'no_hp_pengambil', 'nomor_register', 'file_produk'
    // ];

    public function surat()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }
}
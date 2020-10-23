<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMaiSanPham extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ma_san_pham',
        'ma_loai_san_pham',
        'ma_thuong_hieu',
        'ma_ngay_khuyen_mai',
        'muc_khuyen_mai',
    ];
}

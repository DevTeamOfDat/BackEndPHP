<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ma_nhan_vien',
        'ma_khach_hang',
        'ngay_lap',
        'loai_don',
        'trang_thai',
        'tong_tien',
    ];
}

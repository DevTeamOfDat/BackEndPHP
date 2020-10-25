<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaiKhoanController;
use App\Http\Controllers\NhaCungCapController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('register', [TaiKhoanController::class, 'register']);
Route::post('login', [TaiKhoanController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('info', [TaiKhoanController::class, 'userInfo']);
    Route::post('logout', [TaiKhoanController::class, 'logout']);

    //dành cho trang admin
//nhà cung cấp
    Route::get('/suppliers', [NhaCungCapController::class, 'index']);

    Route::get('/suppliers/{id}', [NhaCungCapController::class, 'show']);

    Route::post('/suppliers', [NhaCungCapController::class, 'store']);

    Route::put('/suppliers/{id}', [NhaCungCapController::class, 'update']);

    Route::delete('/suppliers', [NhaCungCapController::class, 'destroy']);

//tài khoản
    Route::get('/accounts', [TaiKhoanController::class, 'index']);

    Route::get('/accounts/{id}', [TaiKhoanController::class, 'show']);

    Route::post('/accounts', [TaiKhoanController::class, 'store']);

    Route::put('/accounts/{id}', [TaiKhoanController::class, 'update']);

    Route::delete('/accounts', [TaiKhoanController::class, 'destroy']);

//chi tiết hóa đơn
    Route::get('/bill-details', [\App\Http\Controllers\ChiTietHoaDonController::class, 'index']);

    Route::get('/bill-details/{id}', [\App\Http\Controllers\ChiTietHoaDonController::class, 'show']);

    Route::post('/bill-details', [\App\Http\Controllers\ChiTietHoaDonController::class, 'store']);

//    Route::put('/bill-details/{id}', [\App\Http\Controllers\ChiTietHoaDonController::class, 'update']);

    Route::delete('/bill-details', [\App\Http\Controllers\ChiTietHoaDonController::class, 'destroy']);

//chi tiết phiếu nhập
    Route::get('/coupon-details', [\App\Http\Controllers\ChiTietPhieuNhapController::class, 'index']);

    Route::get('/coupon-details/{id}', [\App\Http\Controllers\ChiTietPhieuNhapController::class, 'show']);

    Route::post('/coupon-details', [\App\Http\Controllers\ChiTietPhieuNhapController::class, 'store']);

    //Route::put('/coupon-details/{id}', [\App\Http\Controllers\ChiTietPhieuNhapController::class, 'update']);

    Route::delete('/coupon-details', [\App\Http\Controllers\ChiTietPhieuNhapController::class, 'destroy']);

//đặc trưng
    Route::get('/specialities', [\App\Http\Controllers\DacTrungController::class, 'index']);

    Route::get('/specialities/{id}', [\App\Http\Controllers\DacTrungController::class, 'show']);

    Route::post('/specialities', [\App\Http\Controllers\DacTrungController::class, 'store']);

    Route::put('/specialities/{id}', [\App\Http\Controllers\DacTrungController::class, 'update']);

    Route::delete('/specialities', [\App\Http\Controllers\DacTrungController::class, 'destroy']);

//đặc trưng sản phẩm
    Route::get('/speciality-products', [\App\Http\Controllers\DacTrungSanPhamController::class, 'index']);

    Route::get('/speciality-products/{id}', [\App\Http\Controllers\DacTrungSanPhamController::class, 'show']);

    Route::post('/speciality-products', [\App\Http\Controllers\DacTrungSanPhamController::class, 'store']);

    Route::put('/speciality-products/{id}', [\App\Http\Controllers\DacTrungSanPhamController::class, 'update']);

    Route::delete('/speciality-products', [\App\Http\Controllers\DacTrungSanPhamController::class, 'destroy']);

//hình ảnh sản phẩm
    Route::get('/image-products', [\App\Http\Controllers\HinhAnhSanPhamController::class, 'index']);

    Route::get('/image-products/{id}', [\App\Http\Controllers\HinhAnhSanPhamController::class, 'show']);

    Route::post('/image-products', [\App\Http\Controllers\HinhAnhSanPhamController::class, 'store']);

    Route::put('/image-products/{id}', [\App\Http\Controllers\HinhAnhSanPhamController::class, 'update']);

    Route::delete('/image-products', [\App\Http\Controllers\HinhAnhSanPhamController::class, 'destroy']);

//hóa đơn
    Route::get('/bills', [\App\Http\Controllers\HoaDonController::class, 'index']);

    Route::get('/bills/{id}', [\App\Http\Controllers\HoaDonController::class, 'show']);

    Route::post('/bills', [\App\Http\Controllers\HoaDonController::class, 'store']);

    Route::put('/bills/{id}', [\App\Http\Controllers\HoaDonController::class, 'update']);

    Route::delete('/bills', [\App\Http\Controllers\HoaDonController::class, 'destroy']);

//khuyến mãi sản phẩm
    Route::get('/promotion-products', [\App\Http\Controllers\KhuyenMaiSanPhamController::class, 'index']);

    Route::get('/promotion-products/{id}', [\App\Http\Controllers\KhuyenMaiSanPhamController::class, 'show']);

    Route::post('/promotion-products', [\App\Http\Controllers\KhuyenMaiSanPhamController::class, 'store']);

    Route::put('/promotion-products/{id}', [\App\Http\Controllers\KhuyenMaiSanPhamController::class, 'update']);

    Route::delete('/promotion-products', [\App\Http\Controllers\KhuyenMaiSanPhamController::class, 'destroy']);

//loại đơn
    Route::get('/bill-types', [\App\Http\Controllers\LoaiDonController::class, 'index']);

    Route::get('/bill-types/{id}', [\App\Http\Controllers\LoaiDonController::class, 'show']);

    Route::post('/bill-types', [\App\Http\Controllers\LoaiDonController::class, 'store']);

    Route::put('/bill-types/{id}', [\App\Http\Controllers\LoaiDonController::class, 'update']);

    Route::delete('/bill-types', [\App\Http\Controllers\LoaiDonController::class, 'destroy']);

//loại sản phẩm
    Route::get('/product-types', [\App\Http\Controllers\LoaiSanPhamController::class, 'index']);

    Route::get('/product-types/{id}', [\App\Http\Controllers\LoaiSanPhamController::class, 'show']);

    Route::post('/product-types', [\App\Http\Controllers\LoaiSanPhamController::class, 'store']);

    Route::put('/product-types/{id}', [\App\Http\Controllers\LoaiSanPhamController::class, 'update']);

    Route::delete('/product-types', [\App\Http\Controllers\LoaiSanPhamController::class, 'destroy']);

//loại sản phẩm
    Route::get('/account-types', [\App\Http\Controllers\LoaiTaiKhoanController::class, 'index']);

    Route::get('/account-types/{id}', [\App\Http\Controllers\LoaiTaiKhoanController::class, 'show']);

    Route::post('/account-types', [\App\Http\Controllers\LoaiTaiKhoanController::class, 'store']);

    Route::put('/account-types/{id}', [\App\Http\Controllers\LoaiTaiKhoanController::class, 'update']);

    Route::delete('/account-types', [\App\Http\Controllers\LoaiTaiKhoanController::class, 'destroy']);

//ngày khuyến mãi
    Route::get('/promotion-dates', [\App\Http\Controllers\NgayKhuyenMaiController::class, 'index']);

    Route::get('/promotion-dates/{id}', [\App\Http\Controllers\NgayKhuyenMaiController::class, 'show']);

    Route::post('/promotion-dates', [\App\Http\Controllers\NgayKhuyenMaiController::class, 'store']);

    Route::put('/promotion-dates/{id}', [\App\Http\Controllers\NgayKhuyenMaiController::class, 'update']);

    Route::delete('/promotion-dates', [\App\Http\Controllers\NgayKhuyenMaiController::class, 'destroy']);

//nhận xét
    Route::get('/reviews', [\App\Http\Controllers\NhanXetController::class, 'index']);

    Route::get('/reviews/{id}', [\App\Http\Controllers\NhanXetController::class, 'show']);

    Route::post('/reviews', [\App\Http\Controllers\NhanXetController::class, 'store']);

    Route::put('/reviews/{id}', [\App\Http\Controllers\NhanXetController::class, 'update']);

    Route::delete('/reviews', [\App\Http\Controllers\NhanXetController::class, 'destroy']);

//phiếu nhập
    Route::get('/coupons', [\App\Http\Controllers\PhieuNhapController::class, 'index']);

    Route::get('/coupons/{id}', [\App\Http\Controllers\PhieuNhapController::class, 'show']);

    Route::post('/coupons', [\App\Http\Controllers\PhieuNhapController::class, 'store']);

    Route::put('/coupons/{id}', [\App\Http\Controllers\PhieuNhapController::class, 'update']);

    Route::delete('/coupons', [\App\Http\Controllers\PhieuNhapController::class, 'destroy']);

//sản phẩm
    Route::get('/products', [\App\Http\Controllers\SanPhamController::class, 'index']);

    Route::get('/products/{id}', [\App\Http\Controllers\SanPhamController::class, 'show']);

    Route::post('/products', [\App\Http\Controllers\SanPhamController::class, 'store']);

    Route::put('/products/{id}', [\App\Http\Controllers\SanPhamController::class, 'update']);

    Route::delete('/products', [\App\Http\Controllers\SanPhamController::class, 'destroy']);

//thương hiệu
    Route::get('/trademarks', [\App\Http\Controllers\ThuongHieuController::class, 'index']);

    Route::get('/trademarks/{id}', [\App\Http\Controllers\ThuongHieuController::class, 'show']);

    Route::post('/trademarks', [\App\Http\Controllers\ThuongHieuController::class, 'store']);

    Route::put('/trademarks/{id}', [\App\Http\Controllers\ThuongHieuController::class, 'update']);

    Route::delete('/trademarks', [\App\Http\Controllers\ThuongHieuController::class, 'destroy']);

//tin tức
    Route::get('/hotnews', [\App\Http\Controllers\TinTucController::class, 'index']);

    Route::get('/hotnews/{id}', [\App\Http\Controllers\TinTucController::class, 'show']);

    Route::post('/hotnews', [\App\Http\Controllers\TinTucController::class, 'store']);

    Route::put('/hotnews/{id}', [\App\Http\Controllers\TinTucController::class, 'update']);

    Route::delete('/hotnews', [\App\Http\Controllers\TinTucController::class, 'destroy']);

//trạng thái
    Route::get('/status', [\App\Http\Controllers\TrangThaiController::class, 'index']);

    Route::get('/status/{id}', [\App\Http\Controllers\TrangThaiController::class, 'show']);

    Route::post('/status', [\App\Http\Controllers\TrangThaiController::class, 'store']);

    Route::put('/status/{id}', [\App\Http\Controllers\TrangThaiController::class, 'update']);

    Route::delete('/status', [\App\Http\Controllers\TrangThaiController::class, 'destroy']);

//voicher
    Route::get('/voichers', [\App\Http\Controllers\VoicherController::class, 'index']);

    Route::get('/voichers/{id}', [\App\Http\Controllers\VoicherController::class, 'show']);

    Route::post('/voichers', [\App\Http\Controllers\VoicherController::class, 'store']);

    Route::put('/voichers/{id}', [\App\Http\Controllers\VoicherController::class, 'update']);

    Route::delete('/voichers', [\App\Http\Controllers\VoicherController::class, 'destroy']);

//giỏ hàng
//    Route::get('/carts/{query}', [\App\Http\Controllers\GioHangController::class, 'index']);
//
//    Route::get('/carts/{id}', [\App\Http\Controllers\GioHangController::class, 'show']);
//
//    Route::post('/carts', [\App\Http\Controllers\GioHangController::class, 'store']);
//
//    Route::put('/carts/{id}', [\App\Http\Controllers\GioHangController::class, 'update']);
//
//    Route::delete('/carts', [\App\Http\Controllers\GioHangController::class, 'destroy']);
});
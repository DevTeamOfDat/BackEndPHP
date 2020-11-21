<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    const quy1 = [1, 2, 3];
    const quy2 = [4, 5, 6];
    const quy3 = [7, 8, 9];
    const quy4 = [10, 11, 12];

    public function checkUser()
    {
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            return true;
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
        }
    }

    //báo cáo danh sách các sản phẩm tồn kho
    public function inventoryProduct()
    {
        if ($this->checkUser()) {
            $objs = DB::table('inventoryProducts')->get();
            return response()->json(['data' => $objs], 200);
        }
    }

    //báo cáo danh sách phiếu nhập và tổng tiền nhập hàng theo tháng, quý, năm
    public function reportCoupons(Request $request)
    {
        if ($this->checkUser()) {
            $key = $request->get('key');
            $params = null;
            try {
                $param = $request->get('param');
                if (str_contains($param, '/')) {
                    $params = explode('/', $param);
                } elseif (str_contains($param, '-')) {
                    $params = explode('-', $param);
                } else {
                    $params = $param;
                }
            } catch (\Throwable $e) {

            }
            $objs = null;
            $code = 200;
            switch ($key) {
                case 'all':
                    $objs = DB::table(PhieuNhapController::table)
                        ->leftJoin(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', PhieuNhapController::table . '.' . PhieuNhapController::ma_nha_cung_cap)
                        ->leftJoin(TaiKhoanController::table, PhieuNhapController::table . '.' . PhieuNhapController::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                        ->select(PhieuNhapController::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                        ->where(PhieuNhapController::table . '.' . PhieuNhapController::isActive, '=', true)
                        ->orderByDesc('tong_tien')
                        ->get();
                    break;
                case 'bct':
                    $objs = DB::table(PhieuNhapController::table)
                        ->leftJoin(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', PhieuNhapController::table . '.' . PhieuNhapController::ma_nha_cung_cap)
                        ->leftJoin(TaiKhoanController::table, PhieuNhapController::table . '.' . PhieuNhapController::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                        ->select(PhieuNhapController::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                        ->where(PhieuNhapController::table . '.' . PhieuNhapController::isActive, '=', true)
                        ->whereMonth(PhieuNhapController::ngay_nhap, '=', $params[0])
                        ->whereYear(PhieuNhapController::ngay_nhap, '=', $params[1])
                        ->orderByDesc('tong_tien')
                        ->get();
                    break;
                case 'bcq':
                    switch ($params[0]) {
                        case '1':
                            $objs = DB::table(PhieuNhapController::table)
                                ->leftJoin(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', PhieuNhapController::table . '.' . PhieuNhapController::ma_nha_cung_cap)
                                ->leftJoin(TaiKhoanController::table, PhieuNhapController::table . '.' . PhieuNhapController::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                                ->select(PhieuNhapController::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                                ->where(PhieuNhapController::table . '.' . PhieuNhapController::isActive, '=', true)
                                ->whereMonth(PhieuNhapController::ngay_nhap, '>=', self::quy1[0])
                                ->whereMonth(PhieuNhapController::ngay_nhap, '<=', self::quy1[2])
                                ->whereYear(PhieuNhapController::ngay_nhap, '=', $params[1])
                                ->orderByDesc('tong_tien')
                                ->get();
                            break;
                        case '2':
                            $objs = DB::table(PhieuNhapController::table)
                                ->leftJoin(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', PhieuNhapController::table . '.' . PhieuNhapController::ma_nha_cung_cap)
                                ->leftJoin(TaiKhoanController::table, PhieuNhapController::table . '.' . PhieuNhapController::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                                ->select(PhieuNhapController::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                                ->where(PhieuNhapController::table . '.' . PhieuNhapController::isActive, '=', true)
                                ->whereMonth(PhieuNhapController::ngay_nhap, '>=', self::quy2[0])
                                ->whereMonth(PhieuNhapController::ngay_nhap, '<=', self::quy2[2])
                                ->whereYear(PhieuNhapController::ngay_nhap, '=', $params[1])
                                ->orderByDesc('tong_tien')
                                ->get();
                            break;
                        case '3':
                            $objs = DB::table(PhieuNhapController::table)
                                ->leftJoin(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', PhieuNhapController::table . '.' . PhieuNhapController::ma_nha_cung_cap)
                                ->leftJoin(TaiKhoanController::table, PhieuNhapController::table . '.' . PhieuNhapController::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                                ->select(PhieuNhapController::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                                ->where(PhieuNhapController::table . '.' . PhieuNhapController::isActive, '=', true)
                                ->whereMonth(PhieuNhapController::ngay_nhap, '>=', self::quy3[0])
                                ->whereMonth(PhieuNhapController::ngay_nhap, '<=', self::quy3[2])
                                ->whereYear(PhieuNhapController::ngay_nhap, '=', $params[1])
                                ->orderByDesc('tong_tien')
                                ->get();
                            break;
                        case '4':
                            $objs = DB::table(PhieuNhapController::table)
                                ->leftJoin(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', PhieuNhapController::table . '.' . PhieuNhapController::ma_nha_cung_cap)
                                ->leftJoin(TaiKhoanController::table, PhieuNhapController::table . '.' . PhieuNhapController::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                                ->select(PhieuNhapController::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                                ->where(PhieuNhapController::table . '.' . PhieuNhapController::isActive, '=', true)
                                ->whereMonth(PhieuNhapController::ngay_nhap, '>=', self::quy4[0])
                                ->whereMonth(PhieuNhapController::ngay_nhap, '<=', self::quy4[2])
                                ->whereYear(PhieuNhapController::ngay_nhap, '=', $params[1])
                                ->orderByDesc('tong_tien')
                                ->get();
                            break;
                    }
                    break;
                case 'bcn':
                    $objs = DB::table(PhieuNhapController::table)
                        ->leftJoin(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', PhieuNhapController::table . '.' . PhieuNhapController::ma_nha_cung_cap)
                        ->leftJoin(TaiKhoanController::table, PhieuNhapController::table . '.' . PhieuNhapController::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                        ->select(PhieuNhapController::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                        ->where(PhieuNhapController::table . '.' . PhieuNhapController::isActive, '=', true)
                        ->whereYear(PhieuNhapController::ngay_nhap, '=', $params)
                        ->orderByDesc('tong_tien')
                        ->get();
                    break;
                default:
                    $objs = DB::table(PhieuNhapController::table)
                        ->leftJoin(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', PhieuNhapController::table . '.' . PhieuNhapController::ma_nha_cung_cap)
                        ->leftJoin(TaiKhoanController::table, PhieuNhapController::table . '.' . PhieuNhapController::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                        ->select(PhieuNhapController::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                        ->where(PhieuNhapController::table . '.' . PhieuNhapController::isActive, '=', true)
                        ->orderByDesc('tong_tien')
                        ->get();
                    break;
            }
            return response()->json(['data' => $objs], $code);
        }
    }

    //báo cáo danh sách hóa đơn và tổng tiền bán hàng theo tháng, quý, năm
    public function reportBills(Request $request)
    {
        if ($this->checkUser()) {
            $key = $request->get('key');
            $params = null;
            try {
                $param = $request->get('param');
                if (str_contains($param, '/')) {
                    $params = explode('/', $param);
                } elseif (str_contains($param, '-')) {
                    $params = explode('-', $param);
                } else {
                    $params = $param;
                }
            } catch (\Throwable $e) {

            }
            $objs = null;
            $code = 200;
            switch ($key) {
                case 'all':
                    $objs = DB::table(HoaDonController::table)
                        ->leftJoin(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', HoaDonController::table . '.' . HoaDonController::loai_don)
                        ->leftJoin(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', HoaDonController::table . '.' . HoaDonController::trang_thai)
                        ->select(HoaDonController::id, TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as loai_don', HoaDonController::thanh_tien, HoaDonController::ngay_lap)
                        ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                        ->orderByDesc('thanh_tien')
                        ->get();
                    break;
                case 'bct':
                    $objs = DB::table(HoaDonController::table)
                        ->leftJoin(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', HoaDonController::table . '.' . HoaDonController::loai_don)
                        ->leftJoin(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', HoaDonController::table . '.' . HoaDonController::trang_thai)
                        ->select(HoaDonController::id, TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as loai_don', HoaDonController::thanh_tien, HoaDonController::ngay_lap)
                        ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                        ->whereMonth(HoaDonController::ngay_lap, '=', $params[0])
                        ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                        ->orderByDesc('thanh_tien')
                        ->get();
                    break;
                case 'bcq':
                    switch ($params[0]) {
                        case '1':
                            $objs = DB::table(HoaDonController::table)
                                ->leftJoin(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', HoaDonController::table . '.' . HoaDonController::loai_don)
                                ->leftJoin(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', HoaDonController::table . '.' . HoaDonController::trang_thai)
                                ->select(HoaDonController::id, TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as loai_don', HoaDonController::thanh_tien, HoaDonController::ngay_lap)
                                ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                                ->whereMonth(HoaDonController::ngay_lap, '>=', self::quy1[0])
                                ->whereMonth(HoaDonController::ngay_lap, '<=', self::quy1[2])
                                ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                                ->orderByDesc('thanh_tien')
                                ->get();
                            break;
                        case '2':
                            $objs = DB::table(HoaDonController::table)
                                ->leftJoin(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', HoaDonController::table . '.' . HoaDonController::loai_don)
                                ->leftJoin(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', HoaDonController::table . '.' . HoaDonController::trang_thai)
                                ->select(HoaDonController::id, TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as loai_don', HoaDonController::thanh_tien, HoaDonController::ngay_lap)
                                ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                                ->whereMonth(HoaDonController::ngay_lap, '>=', self::quy2[0])
                                ->whereMonth(HoaDonController::ngay_lap, '<=', self::quy2[2])
                                ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                                ->orderByDesc('thanh_tien')
                                ->get();
                            break;
                        case '3':
                            $objs = DB::table(HoaDonController::table)
                                ->leftJoin(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', HoaDonController::table . '.' . HoaDonController::loai_don)
                                ->leftJoin(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', HoaDonController::table . '.' . HoaDonController::trang_thai)
                                ->select(HoaDonController::id, TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as loai_don', HoaDonController::thanh_tien, HoaDonController::ngay_lap)
                                ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                                ->whereMonth(HoaDonController::ngay_lap, '>=', self::quy3[0])
                                ->whereMonth(HoaDonController::ngay_lap, '<=', self::quy3[2])
                                ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                                ->orderByDesc('thanh_tien')
                                ->get();
                            break;
                        case '4':
                            $objs = DB::table(HoaDonController::table)
                                ->leftJoin(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', HoaDonController::table . '.' . HoaDonController::loai_don)
                                ->leftJoin(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', HoaDonController::table . '.' . HoaDonController::trang_thai)
                                ->select(HoaDonController::id, TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as loai_don', HoaDonController::thanh_tien, HoaDonController::ngay_lap)
                                ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                                ->whereMonth(HoaDonController::ngay_lap, '>=', self::quy4[0])
                                ->whereMonth(HoaDonController::ngay_lap, '<=', self::quy4[2])
                                ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                                ->orderByDesc('thanh_tien')
                                ->get();
                            break;
                    }
                    break;
                case 'bcn':
                    $objs = DB::table(HoaDonController::table)
                        ->leftJoin(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', HoaDonController::table . '.' . HoaDonController::loai_don)
                        ->leftJoin(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', HoaDonController::table . '.' . HoaDonController::trang_thai)
                        ->select(HoaDonController::id, TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as loai_don', HoaDonController::thanh_tien, HoaDonController::ngay_lap)
                        ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                        ->whereYear(HoaDonController::ngay_lap, '=', $params)
                        ->orderByDesc('thanh_tien')
                        ->get();
                    break;
                default:
                    $objs = DB::table(HoaDonController::table)
                        ->leftJoin(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', HoaDonController::table . '.' . HoaDonController::loai_don)
                        ->leftJoin(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', HoaDonController::table . '.' . HoaDonController::trang_thai)
                        ->select(HoaDonController::id, TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as loai_don', HoaDonController::thanh_tien, HoaDonController::ngay_lap)
                        ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                        ->orderByDesc('thanh_tien')
                        ->get();
                    break;
            }
            return response()->json(['data' => $objs], $code);
        }
    }

    //báo cáo danh sách họ tên, tổng tiền của từng nhân viên bán hàng theo tháng, quý, năm
    public function reportEmployees(Request $request)
    {
        if ($this->checkUser()) {
            $key = $request->get('key');
            $params = null;
            try {
                $param = $request->get('param');
                if (str_contains($param, '/')) {
                    $params = explode('/', $param);
                } elseif (str_contains($param, '-')) {
                    $params = explode('-', $param);
                } else {
                    $params = $param;
                }
            } catch (\Throwable $e) {

            }
            $objs = null;
            $code = 200;
            switch ($key) {
                case 'all':
                    $objs = DB::table(TaiKhoanController::table)
                        ->select(TaiKhoanController::id, TaiKhoanController::email, TaiKhoanController::ho_ten, TaiKhoanController::dia_chi, TaiKhoanController::so_dien_thoai, TaiKhoanController::hinh_anh, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta . ' as loai_tai_khoan', HoaDonController::ngay_lap, DB::raw('SUM(thanh_tien) as tong_tien'))
                        ->leftJoin(HoaDonController::table, HoaDonController::table . '.' . HoaDonController::ma_nv, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                        ->join(LoaiTaiKhoanController::table, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri, '=', TaiKhoanController::loai_tai_khoan)
                        ->where(TaiKhoanController::loai_tai_khoan, '=', 'NV')
                        ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                        ->groupBy(TaiKhoanController::id)
                        ->orderByDesc('tong_tien')
                        ->get();
                    break;
                case 'bct':
                    $objs = DB::table(TaiKhoanController::table)
                        ->select(TaiKhoanController::id, TaiKhoanController::email, TaiKhoanController::ho_ten, TaiKhoanController::dia_chi, TaiKhoanController::so_dien_thoai, TaiKhoanController::hinh_anh, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta . ' as loai_tai_khoan', HoaDonController::ngay_lap, DB::raw('SUM(thanh_tien) as tong_tien'))
                        ->leftJoin(HoaDonController::table, HoaDonController::table . '.' . HoaDonController::ma_nv, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                        ->join(LoaiTaiKhoanController::table, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri, '=', TaiKhoanController::loai_tai_khoan)
                        ->where(TaiKhoanController::loai_tai_khoan, '=', 'NV')
                        ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                        ->whereMonth(HoaDonController::ngay_lap, '=', $params[0])
                        ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                        ->groupBy(TaiKhoanController::id)
                        ->orderByDesc('tong_tien')
                        ->get();
                    break;
                case 'bcq':
                    switch ($params[0]) {
                        case '1':
                            $objs = DB::table(TaiKhoanController::table)
                                ->select(TaiKhoanController::id, TaiKhoanController::email, TaiKhoanController::ho_ten, TaiKhoanController::dia_chi, TaiKhoanController::so_dien_thoai, TaiKhoanController::hinh_anh, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta . ' as loai_tai_khoan', HoaDonController::ngay_lap, DB::raw('SUM(thanh_tien) as tong_tien'))
                                ->leftJoin(HoaDonController::table, HoaDonController::table . '.' . HoaDonController::ma_nv, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                                ->join(LoaiTaiKhoanController::table, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri, '=', TaiKhoanController::loai_tai_khoan)
                                ->where(TaiKhoanController::loai_tai_khoan, '=', 'NV')
                                ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                                ->whereMonth(HoaDonController::ngay_lap, '>=', self::quy1[0])
                                ->whereMonth(HoaDonController::ngay_lap, '<=', self::quy1[2])
                                ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                                ->groupBy(TaiKhoanController::id)
                                ->orderByDesc('tong_tien')
                                ->get();
                            break;
                        case '2':
                            $objs = DB::table(TaiKhoanController::table)
                                ->select(TaiKhoanController::id, TaiKhoanController::email, TaiKhoanController::ho_ten, TaiKhoanController::dia_chi, TaiKhoanController::so_dien_thoai, TaiKhoanController::hinh_anh, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta . ' as loai_tai_khoan', HoaDonController::ngay_lap, DB::raw('SUM(thanh_tien) as tong_tien'))
                                ->leftJoin(HoaDonController::table, HoaDonController::table . '.' . HoaDonController::ma_nv, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                                ->join(LoaiTaiKhoanController::table, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri, '=', TaiKhoanController::loai_tai_khoan)
                                ->where(TaiKhoanController::loai_tai_khoan, '=', 'NV')
                                ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                                ->whereMonth(HoaDonController::ngay_lap, '>=', self::quy2[0])
                                ->whereMonth(HoaDonController::ngay_lap, '<=', self::quy2[2])
                                ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                                ->groupBy(TaiKhoanController::id)
                                ->orderByDesc('tong_tien')
                                ->get();
                            break;
                        case '3':
                            $objs = DB::table(TaiKhoanController::table)
                                ->select(TaiKhoanController::id, TaiKhoanController::email, TaiKhoanController::ho_ten, TaiKhoanController::dia_chi, TaiKhoanController::so_dien_thoai, TaiKhoanController::hinh_anh, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta . ' as loai_tai_khoan', HoaDonController::ngay_lap, DB::raw('SUM(thanh_tien) as tong_tien'))
                                ->leftJoin(HoaDonController::table, HoaDonController::table . '.' . HoaDonController::ma_nv, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                                ->join(LoaiTaiKhoanController::table, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri, '=', TaiKhoanController::loai_tai_khoan)
                                ->where(TaiKhoanController::loai_tai_khoan, '=', 'NV')
                                ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                                ->whereMonth(HoaDonController::ngay_lap, '>=', self::quy3[0])
                                ->whereMonth(HoaDonController::ngay_lap, '<=', self::quy3[2])
                                ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                                ->groupBy(TaiKhoanController::id)
                                ->orderByDesc('tong_tien')
                                ->get();
                            break;
                        case '4':
                            $objs = DB::table(TaiKhoanController::table)
                                ->select(TaiKhoanController::id, TaiKhoanController::email, TaiKhoanController::ho_ten, TaiKhoanController::dia_chi, TaiKhoanController::so_dien_thoai, TaiKhoanController::hinh_anh, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta . ' as loai_tai_khoan', HoaDonController::ngay_lap, DB::raw('SUM(thanh_tien) as tong_tien'))
                                ->leftJoin(HoaDonController::table, HoaDonController::table . '.' . HoaDonController::ma_nv, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                                ->join(LoaiTaiKhoanController::table, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri, '=', TaiKhoanController::loai_tai_khoan)
                                ->where(TaiKhoanController::loai_tai_khoan, '=', 'NV')
                                ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                                ->whereMonth(HoaDonController::ngay_lap, '>=', self::quy4[0])
                                ->whereMonth(HoaDonController::ngay_lap, '<=', self::quy4[2])
                                ->whereYear(HoaDonController::ngay_lap, '=', $params[1])
                                ->groupBy(TaiKhoanController::id)
                                ->orderByDesc('tong_tien')
                                ->get();
                            break;
                    }
                    break;
                case 'bcn':
                    $objs = DB::table(TaiKhoanController::table)
                        ->select(TaiKhoanController::id, TaiKhoanController::email, TaiKhoanController::ho_ten, TaiKhoanController::dia_chi, TaiKhoanController::so_dien_thoai, TaiKhoanController::hinh_anh, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta . ' as loai_tai_khoan', HoaDonController::ngay_lap, DB::raw('SUM(thanh_tien) as tong_tien'))
                        ->leftJoin(HoaDonController::table, HoaDonController::table . '.' . HoaDonController::ma_nv, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                        ->join(LoaiTaiKhoanController::table, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri, '=', TaiKhoanController::loai_tai_khoan)
                        ->where(TaiKhoanController::loai_tai_khoan, '=', 'NV')
                        ->where(HoaDonController::table . '.' . HoaDonController::isActive, '=', true)
                        ->whereYear(HoaDonController::ngay_lap, '=', $params)
                        ->groupBy(TaiKhoanController::id)
                        ->orderByDesc('tong_tien')
                        ->get();
                    break;
                default:
                    $objs = DB::table(TaiKhoanController::table)
                        ->select(TaiKhoanController::id, TaiKhoanController::email, TaiKhoanController::ho_ten, TaiKhoanController::dia_chi, TaiKhoanController::so_dien_thoai, TaiKhoanController::hinh_anh, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta . ' as loai_tai_khoan', HoaDonController::ngay_lap, DB::raw('SUM(thanh_tien) as tong_tien'))
                        ->leftJoin(HoaDonController::table, HoaDonController::table . '.' . HoaDonController::ma_nv, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                        ->join(LoaiTaiKhoanController::table, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri, '=', TaiKhoanController::loai_tai_khoan)
                        ->where(TaiKhoanController::loai_tai_khoan, '=', 'NV')
                        ->where(HoaDonController::isActive, '=', true)
                        ->groupBy(TaiKhoanController::id)
                        ->orderByDesc('tong_tien')
                        ->get();
                    break;
            }
            return response()->json(['data' => $objs], $code);
        }
    }

}

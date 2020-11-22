<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Cknow\Money\Money;

class HoaDonController extends Controller
{
    private $base;
    const table = 'hoa_dons';
    const id = 'ma_hoa_don';
    const ma_nv = 'ma_nhan_vien';
    const ma_kh = 'ma_khach_hang';
    const ma_voucher = 'ma_voucher';
    const ngay_lap = 'ngay_lap';
    const loai_don = 'loai_don';
    const trang_thai = 'trang_thai';
    const tong_tien = 'tong_tien';
    const thanh_tien = 'thanh_tien';
    const isActive = 'isActive';

    /**
     * NhaCungCapController constructor.
     * @param $base
     */
    public function __construct()
    {
        $this->base = new BaseController(self::table, self::id, self::isActive);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $objs = null;
            $code = null;
            $objs = DB::table(self::table)
                ->leftJoin(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
                ->leftJoin(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
                ->leftJoin(VoucherController::table, VoucherController::table . '.' . VoucherController::id, self::table . '.' . self::ma_voucher)
                ->select(self::table . '.*', 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang', VoucherController::table . '.' . VoucherController::muc_voucher)
                ->get();
            $code = 200;
            return response()->json(['data' => $objs], $code);
        } else {
            $objs = null;
            $code = null;
            $objs = DB::table(self::table)
                ->join(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
                ->join(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
                ->select(self::id, 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang', self::ngay_lap, self::loai_don, self::trang_thai, self::tong_tien)
                ->where(self::table . '.' . self::ma_kh, '=', $user->ma_tai_khoan)
                ->get();
            $code = 200;
            return response()->json(['data' => $objs], $code);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set(BaseController::timezone);
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        $ma_tk = $user->ma_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $arr_value = [];
            $arr_value[self::ma_nv] = $ma_tk;
            if ($request->ma_khach_hang) {
                $arr_value[self::ma_kh] = $request->ma_khach_hang;
            }
            $arr_value[self::ngay_lap] = date('Y-m-d');
            $arr_value[self::loai_don] = 2;
            $arr_value[self::trang_thai] = 1;
            DB::table(self::table)->insert($request->all());
            return response()->json(['success' => "Thêm mới thành công"], 201);
        } else {
            $arr_value = [];
            $arr_value[self::ma_kh] = $ma_tk;
            $arr_value[self::ngay_lap] = date('Y-m-d');
            $arr_value[self::loai_don] = 1;
            $arr_value[self::trang_thai] = 2;
            DB::table(self::table)->insert($arr_value);
            return response()->json(['success' => "Thêm mới thành công"], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        setlocale(LC_MONETARY,"en_US");
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $obj = DB::table(self::table)
                ->join(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
                ->join(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
                ->join(TrangThaiController::table, TrangThaiController::table . '.' . TrangThaiController::id, '=', self::table . '.' . self::trang_thai)
                ->join(LoaiDonController::table, LoaiDonController::table . '.' . LoaiDonController::id, '=', self::table . '.' . self::loai_don)
                ->select(self::table . '.*', 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang', TrangThaiController::table . '.' . TrangThaiController::mo_ta . ' as gia_tri_trang_thai', LoaiDonController::table . '.' . LoaiDonController::mo_ta . ' as gia_tri_loai_don')
                ->where(self::table . '.' . self::id, '=', $id)
                ->get();
            if ($obj) {
                return response()->json([
                    'data' => $obj
                ], 200);
            } else {
                return response()->json(['error' => 'Không tìm thấy'], 200);
            }
        } else {
            $obj = DB::table(self::table)
                ->join(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
                ->join(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
                ->select(self::id, 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang', self::ngay_lap, self::loai_don, self::trang_thai, self::tong_tien)
                ->where(self::table . '.' . self::id, '=', $id)
                ->where(self::table . '.' . self::ma_kh, '=', $user->ma_tai_khoan)
                ->get();
            if ($obj) {
                return response()->json([
                    'data' => $obj
                ], 200);
            } else {
                return response()->json(['error' => 'Không tìm thấy'], 200);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $hd = DB::table(self::table)->where(self::id, '=', $id)->first();
            if ($hd->trang_thai == false && $request->get(self::trang_thai) == true) {
                DB::table(self::table)->where(self::id, '=', $id)->update([self::trang_thai => true]);
                return response()->json(['success' => 'Cập nhật thành công'], 201);
            } else {
                return response()->json(['error' => 'Cập nhật thất bại'], 400);
            }
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            try {
                if ($listId = $request->get(BaseController::listId)) {
                    DB::table(ChiTietHoaDonController::table)->whereIn(ChiTietHoaDonController::ma_hoa_don, $listId)
                        ->where(ChiTietHoaDonController::isActive, '=', true)
                        ->update([ChiTietHoaDonController::isActive => false]);
                    DB::table(VoucherController::table)->join(self::table, self::table . '.' . self::ma_voucher, '=', VoucherController::table . '.' . VoucherController::id)
                        ->whereIn(self::table . '.' . self::id, $listId)
                        ->update([ChiTietHoaDonController::isActive => true]);
                } else {
                    $id = $request->get(BaseController::key_id);
                    DB::table(ChiTietHoaDonController::table)->where(ChiTietHoaDonController::ma_hoa_don, $id)
                        ->where(ChiTietHoaDonController::isActive, '=', true)
                        ->update([ChiTietHoaDonController::isActive => false]);
                    DB::table(VoucherController::table)->join(self::table, self::table . '.' . self::ma_voucher, '=', VoucherController::table . '.' . VoucherController::id)
                        ->where(self::table . '.' . self::id, $id)
                        ->update([ChiTietHoaDonController::isActive => true]);
                }
            } catch (\Throwable $e) {
                report($e);
            }
            $this->base->destroy($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
        }
    }
}

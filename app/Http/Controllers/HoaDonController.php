<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
//        switch ($query) {
//            case "all":
//                $objs = DB::table(self::table)
//                    ->join(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
//                    ->join(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
//                    ->select(self::table . '.*', 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang')
//                    ->get();
//                $code = 200;
//                break;
//            case "active":
//                $objs = DB::table(self::table)
//                    ->join(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
//                    ->join(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
//                    ->select(self::table . '.*', 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang')
//                    ->where(self::table . '.' . self::isActive, '=', true)->get();
//                $code = 200;
//                break;
//            case "inactive":
//                $objs = DB::table(self::table)
//                    ->join(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
//                    ->join(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
//                    ->select(self::table . '.*', 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang')
//                    ->where(self::table . '.' . self::isActive, '=', false)->get();
//                $code = 200;
//                break;
//            default:
//                $objs = "Không tìm thấy";
//                $code = 200;
//                break;
//        }
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
            $arr_value[self::ngay_lap] = date('d-m-Y');
            $arr_value[self::loai_don] = false;
            $arr_value[self::trang_thai] = true;
            DB::table(self::table)->insert($arr_value);
            return response()->json(['success' => "Thêm mới thành công"], 201);
        } else {
            $arr_value = [];
            $arr_value[self::ma_kh] = $ma_tk;
            $arr_value[self::ngay_lap] = date('d-m-Y');
            $arr_value[self::loai_don] = true;
            $arr_value[self::trang_thai] = false;
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
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $obj = DB::table(self::table)
                ->join(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
                ->join(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
                ->select(self::table . '.*', 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang')
                ->where(self::table . '.' . self::id, '=', $id)
                ->get();

            $listBillDetail = DB::table(ChiTietHoaDonController::table)
                ->join(SanPhamController::table, ChiTietHoaDonController::table . '.' . ChiTietHoaDonController::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                ->select(ChiTietHoaDonController::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
                ->where(ChiTietHoaDonController::ma_hoa_don, '=', $id)
                ->get();
            if ($obj) {
                return response()->json([
                    'data' => $obj,
                    'listBillDetail' => $listBillDetail
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
            $listBillDetail = DB::table(ChiTietHoaDonController::table)
                ->join(SanPhamController::table, ChiTietHoaDonController::table . '.' . ChiTietHoaDonController::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                ->select(ChiTietHoaDonController::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
                ->where(ChiTietHoaDonController::ma_hoa_don, '=', $id)
                ->where(self::table . '.' . self::ma_kh, '=', $user->ma_tai_khoan)
                ->get();
            if ($obj) {
                return response()->json([
                    'data' => $obj,
                    'listBillDetail' => $listBillDetail
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
                return response()->json(['success' => 'Cập nhật thành công'], 200);
            } else {
                return response()->json(['error' => 'Cập nhật thất bại'], 200);
            }
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
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
            $this->base->destroy($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
        }
    }
}

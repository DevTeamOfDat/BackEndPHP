<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SanPhamController extends Controller
{
    private $base;
    const table = 'san_phams';
    const id = 'ma_san_pham';
    const ma_thuong_hieu = 'ma_thuong_hieu';
    const ma_loai_san_pham = 'ma_loai_san_pham';
    const ten_san_pham = 'ten_san_pham';
    const gia_ban = 'gia_ban';
    const so_luong = 'so_luong';
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
        date_default_timezone_set(BaseController::timezone);
        $date = date('d-m-Y');
//        $user = auth()->user();
//        $loai_tk = $user->loai_tai_khoan;
//        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
        $objs = null;
        $code = null;
        $objs = DB::table(self::table)
            ->join(ThuongHieuController::table, self::table . '.' . self::ma_thuong_hieu, '=', ThuongHieuController::table . '.' . ThuongHieuController::id)
            ->join(LoaiSanPhamController::table, self::table . '.' . self::ma_loai_san_pham, '=', LoaiSanPhamController::table . '.' . LoaiSanPhamController::id)
            ->leftJoin(KhuyenMaiSanPhamController::table, self::table . '.' . self::id, KhuyenMaiSanPhamController::table . '.' . KhuyenMaiSanPhamController::ma_san_pham)
            ->leftJoin(NgayKhuyenMaiController::table, NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::id, KhuyenMaiSanPhamController::table . '.' . KhuyenMaiSanPhamController::ma_ngay_khuyen_mai)
            ->select(self::table . '.*', ThuongHieuController::table . '.' . ThuongHieuController::ten_thuong_hieu, LoaiSanPhamController::table . '.' . LoaiSanPhamController::ten_loai_san_pham, KhuyenMaiSanPhamController::muc_khuyen_mai, (self::table . '.' . self::gia_ban * (1 - (KhuyenMaiSanPhamController::muc_khuyen_mai / 100))) . 'as giá bán mới')
            ->where(NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::ngay_gio, '=', $date)
            ->get();
        $code = 200;
//        switch ($query) {
//            case "all":
//                $objs = DB::table(self::table)
//                    ->join(ThuongHieuController::table, self::table . '.' . self::ma_thuong_hieu, '=', ThuongHieuController::table . '.' . ThuongHieuController::id)
//                    ->join(LoaiSanPhamController::table, self::table . '.' . self::ma_loai_san_pham, '=', LoaiSanPhamController::table . '.' . LoaiSanPhamController::id)
//                    ->select(self::table . '.*', ThuongHieuController::table . '.' . ThuongHieuController::ten_thuong_hieu, LoaiSanPhamController::table . '.' . LoaiSanPhamController::ten_loai_san_pham)
//                    ->get();
//                $code = 200;
//                break;
//            case "active":
//                $objs = DB::table(self::table)
//                    ->join(ThuongHieuController::table, self::table . '.' . self::ma_thuong_hieu, '=', ThuongHieuController::table . '.' . ThuongHieuController::id)
//                    ->join(LoaiSanPhamController::table, self::table . '.' . self::ma_loai_san_pham, '=', LoaiSanPhamController::table . '.' . LoaiSanPhamController::id)
//                    ->select(self::table . '.*', ThuongHieuController::table . '.' . ThuongHieuController::ten_thuong_hieu, LoaiSanPhamController::table . '.' . LoaiSanPhamController::ten_loai_san_pham)
//                    ->where(self::table . '.' . self::isActive, '=', true)->get();
//                $code = 200;
//                break;
//            case "inactive":
//                $objs = DB::table(self::table)
//                    ->join(ThuongHieuController::table, self::table . '.' . self::ma_thuong_hieu, '=', ThuongHieuController::table . '.' . ThuongHieuController::id)
//                    ->join(LoaiSanPhamController::table, self::table . '.' . self::ma_loai_san_pham, '=', LoaiSanPhamController::table . '.' . LoaiSanPhamController::id)
//                    ->select(self::table . '.*', ThuongHieuController::table . '.' . ThuongHieuController::ten_thuong_hieu, LoaiSanPhamController::table . '.' . LoaiSanPhamController::ten_loai_san_pham)
//                    ->where(self::table . '.' . self::isActive, '=', false)->get();
//                $code = 200;
//                break;
//            default:
//                $objs = "Không tìm thấy";
//                $code = 200;
//                break;
//        }
        return response()->json(['data' => $objs], $code);
//        } else {
//            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
//        }
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
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $validator = Validator::make($request->all(), [
                self::ma_thuong_hieu => 'required',
                self::ma_loai_san_pham => 'required',
                self::ten_san_pham => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 200);
            }

            $this->base->store($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
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
//        $user = auth()->user();
//        $loai_tk = $user->loai_tai_khoan;
//        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
        $obj = DB::table(self::table)
            ->join(ThuongHieuController::table, self::table . '.' . self::ma_thuong_hieu, '=', ThuongHieuController::table . '.' . ThuongHieuController::id)
            ->join(LoaiSanPhamController::table, self::table . '.' . self::ma_loai_san_pham, '=', LoaiSanPhamController::table . '.' . LoaiSanPhamController::id)
            ->select(self::table . '.*', ThuongHieuController::table . '.' . ThuongHieuController::ten_thuong_hieu, LoaiSanPhamController::table . '.' . LoaiSanPhamController::ten_loai_san_pham)
            ->where(self::table . '.' . self::id, '=', $id)
            ->get();
        if ($obj) {
            return response()->json(['data' => $obj], 200);
        } else {
            return response()->json(['error' => 'Không tìm thấy'], 200);
        }
//        } else {
//            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
//        }
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
            $this->base->update($request, $id);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
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

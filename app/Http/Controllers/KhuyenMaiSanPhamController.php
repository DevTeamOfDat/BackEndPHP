<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KhuyenMaiSanPhamController extends Controller
{
    private $base;
    const table = 'khuyen_mai_san_phams';
    const id = 'id';
    const ma_san_pham = 'ma_san_pham';
    const ma_loai_san_pham = 'ma_loai_san_pham';
    const ma_thuong_hieu = 'ma_thuong_hieu';
    const ma_ngay_khuyen_mai = 'ma_ngay_khuyen_mai';
    const muc_khuyen_mai = 'muc_khuyen_mai';
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
    public function index($query)
    {
//        $this->base->index($query);
//        return response()->json($this->base->getMessage(), $this->base->getStatus());

        $objs = null;
        $code = null;
        switch ($query) {
            case "all":
                $objs = DB::table(self::table)
                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                    ->join(LoaiSanPhamController::table, self::table . '.' . self::ma_loai_san_pham, '=', LoaiSanPhamController::table . '.' . LoaiSanPhamController::id)
                    ->join(ThuongHieuController::table, self::table . '.' . self::ma_thuong_hieu, '=', ThuongHieuController::table . '.' . ThuongHieuController::id)
                    ->join(NgayKhuyenMaiController::table, self::table . '.' . self::ma_ngay_khuyen_mai, '=', NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::id)
                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, LoaiSanPhamController::table . '.' . LoaiSanPhamController::ten_loai_san_pham, ThuongHieuController::table . '.' . ThuongHieuController::ten_thuong_hieu, NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::ngay_gio)
                    ->get();
                $code = 200;
                break;
            case "active":
                $objs = DB::table(self::table)
                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                    ->join(LoaiSanPhamController::table, self::table . '.' . self::ma_loai_san_pham, '=', LoaiSanPhamController::table . '.' . LoaiSanPhamController::id)
                    ->join(ThuongHieuController::table, self::table . '.' . self::ma_thuong_hieu, '=', ThuongHieuController::table . '.' . ThuongHieuController::id)
                    ->join(NgayKhuyenMaiController::table, self::table . '.' . self::ma_ngay_khuyen_mai, '=', NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::id)
                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, LoaiSanPhamController::table . '.' . LoaiSanPhamController::ten_loai_san_pham, ThuongHieuController::table . '.' . ThuongHieuController::ten_thuong_hieu, NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::ngay_gio)
                    ->where(self::table . '.' . self::isActive, '=', true)->get();
                $code = 200;
                break;
            case "inactive":
                $objs = DB::table(self::table)
                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                    ->join(LoaiSanPhamController::table, self::table . '.' . self::ma_loai_san_pham, '=', LoaiSanPhamController::table . '.' . LoaiSanPhamController::id)
                    ->join(ThuongHieuController::table, self::table . '.' . self::ma_thuong_hieu, '=', ThuongHieuController::table . '.' . ThuongHieuController::id)
                    ->join(NgayKhuyenMaiController::table, self::table . '.' . self::ma_ngay_khuyen_mai, '=', NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::id)
                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, LoaiSanPhamController::table . '.' . LoaiSanPhamController::ten_loai_san_pham, ThuongHieuController::table . '.' . ThuongHieuController::ten_thuong_hieu, NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::ngay_gio)
                    ->where(self::table . '.' . self::isActive, '=', false)->get();
                $code = 200;
                break;
            default:
                $objs = "Không tìm thấy";
                $code = 200;
                break;
        }
        return response()->json($objs, $code);
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
        $this->validate($request, [
            self::muc_khuyen_mai => 'required',
            self::ma_ngay_khuyen_mai => 'required',
        ]);

        $this->base->store($request);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->base->show($id);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
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
        $this->base->update($request, $id);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->base->destroy($request);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
    }
}

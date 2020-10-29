<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChiTietPhieuNhapController extends Controller
{
    private $base;
    const table = 'chi_tiet_phieu_nhaps';
    const id = 'id';
    const ma_phieu_nhap = 'ma_phieu_nhap';
    const ma_san_pham = 'ma_san_pham';
    const gia_nhap = 'gia_nhap';
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
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $objs = null;
            $code = null;
            $objs = DB::table(self::table)
                ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
                ->get();
            $code = 200;
//        switch ($query) {
//            case "all":
//                $objs = DB::table(self::table)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
//                    ->get();
//                $code = 200;
//                break;
//            case "active":
//                $objs = DB::table(self::table)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
//                    ->where(self::table . '.' . self::isActive, '=', true)->get();
//                $code = 200;
//                break;
//            case "inactive":
//                $objs = DB::table(self::table)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
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
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
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
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            try {
                if ($listObj = $request->get(BaseController::listObj)) {
                    $count = count($listObj);
                    if ($count > 0) {
                        foreach ($listObj as $obj) {
                            $validator = Validator::make($obj, [
                                self::ma_phieu_nhap => 'required',
                                self::ma_san_pham => 'required',
                                self::gia_nhap => 'required',
                                self::so_luong => 'required',
                            ]);
                            if ($validator->fails()) {
                                return response()->json(['error' => $validator->errors()->all()], 200);
                            }
                            if ($obj[self::gia_nhap] < 1) {
                                return response()->json(['error' => 'Giá nhập phải lớn hơn 0'], 200);
                            }
                            if ($obj[self::so_luong] < 1) {
                                return response()->json(['error' => 'Số lượng phải lớn hơn 0'], 200);
                            }
                            if (DB::table(self::table)->where(self::ma_san_pham, '=', $obj[self::ma_san_pham])
                                ->where(self::ma_phieu_nhap, '=', $obj[self::ma_phieu_nhap])
                                ->where(self::isActive, '=', true)->first()) {
                                return response()->json(['error' => 'Thêm mới thất bại. Có 1 row đã tồn tại mã phiếu nhập và mã sản phẩm'], 200);
                            }
                        }
                        foreach ($listObj as $obj) {
                            DB::table(self::table)->insert($obj);
                        }
                        return response()->json(['success' => 'Thêm mới thành công'], 201);
                    } else {
                        return response()->json(['error' => 'Thêm mới thất bại. Không có dữ liệu'], 200);
                    }
                } else {
                    $arr_value = $request->all();
                    if (count($arr_value) > 0) {
                        $validator = Validator::make($arr_value, [
                            self::ma_phieu_nhap => 'required',
                            self::ma_san_pham => 'required',
                            self::gia_nhap => 'required',
                            self::so_luong => 'required',
                        ]);
                        if ($validator->fails()) {
                            return response()->json(['error' => $validator->errors()->all()], 200);
                        }
                        if ($arr_value[self::gia_nhap] < 1) {
                            return response()->json(['error' => 'Giá nhập phải lớn hơn 0'], 200);
                        }
                        if ($arr_value[self::so_luong] < 1) {
                            return response()->json(['error' => 'Số lượng phải lớn hơn 0'], 200);
                        }
                        if (DB::table(self::table)->where(self::ma_san_pham, '=', $arr_value[self::ma_san_pham])
                            ->where(self::ma_phieu_nhap, '=', $arr_value[self::ma_phieu_nhap])
                            ->where(self::isActive, '=', true)->first()) {
                            return response()->json(['error' => 'Thêm mới thất bại. Có 1 row đã tồn tại mã phiếu nhập và mã sản phẩm'], 200);
                        }
                        DB::table(self::table)->insert($arr_value);
                        return response()->json(['success' => 'Thêm mới thành công'], 201);
                    } else {
                        return response()->json(['error' => 'Thêm mới thất bại. Không có dữ liệu'], 200);
                    }
                }
            } catch (\Throwable $e) {
                return response()->json(['error' => $e], 500);
            }
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
        }
    }

    public function showListPN($mapn)
    {
        $this->base->show($mapn);
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
                ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
                ->where(self::table . '.' . self::id, '=', $id)
                ->get();
            if ($obj) {
                return response()->json(['data' => $obj], 200);
            } else {
                return response()->json(['error' => 'Không tìm thấy'], 200);
            }
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
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

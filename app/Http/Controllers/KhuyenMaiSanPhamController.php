<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KhuyenMaiSanPhamController extends Controller
{
    private $base;
    const table = 'khuyen_mai_san_phams';
    const id = 'id';
    const ma_san_pham = 'ma_san_pham';
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
    public function index()
    {
        date_default_timezone_set(BaseController::timezone);
        $date = date('d-m-Y');
//        $ngay_km = DB::table(NgayKhuyenMaiController::table)
//            ->where(NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::ngay_gio, '<', $date)
//            ->where(NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::isActive, '=', true)
//            ->get(NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::id);

//        DB::table(NgayKhuyenMaiController::table)
//            ->whereIn(NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::id, $ngay_km)
//            ->update([NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::isActive => false]);

        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $this->base->index();
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            $objs = DB::table(self::table)
                ->leftJoin(SanPhamController::table, SanPhamController::table . '.' . SanPhamController::id, '=', self::table . '.' . self::ma_san_pham)
                ->join(NgayKhuyenMaiController::table, NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::id, '=', self::table . '.' . self::ma_ngay_khuyen_mai)
                ->select(self::table . '.' . self::id, self::table . '.' . self::ma_san_pham, SanPhamController::table . '.' . SanPhamController::ten_san_pham, self::table . '.' . self::ma_ngay_khuyen_mai, NgayKhuyenMaiController::table . '.' . NgayKhuyenMaiController::ngay_gio, self::muc_khuyen_mai)
                ->where(self::table . '.' . self::isActive, '=', true)
                ->where(NgayKhuyenMaiController::ngay_gio, '=', $date)
                ->get();
            return response()->json(['data' => $objs], 200);
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
                                self::ma_ngay_khuyen_mai => 'required',
                                self::muc_khuyen_mai => 'required',
                            ]);
                            if ($validator->fails()) {
                                return response()->json(['error' => $validator->errors()->all()], 400);
                            }
                        }
                    } else {
                        return response()->json(['error' => 'Thêm mới thất bại. Không có dữ liệu'], 400);
                    }
                } else {
                    $arr_value = $request->all();
                    if (count($arr_value) > 0) {
                        $validator = Validator::make($arr_value, [
                            self::ma_ngay_khuyen_mai => 'required',
                            self::muc_khuyen_mai => 'required',
                        ]);
                        if ($validator->fails()) {
                            return response()->json(['error' => $validator->errors()->all()], 400);
                        }
                    } else {
                        return response()->json(['error' => 'Thêm mới thất bại. Không có dữ liệu'], 400);
                    }
                }
            } catch (\Throwable $e) {
                return response()->json(['error' => $e], 500);
            }

            $this->base->store($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
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
        //
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
        //
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
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
        }
    }
}

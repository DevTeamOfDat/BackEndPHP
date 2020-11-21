<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NgayKhuyenMaiController extends Controller
{
    private $base;
    const table = 'ngay_khuyen_mais';
    const id = 'ma_ngay_khuyen_mai';
    const ngay_gio = 'ngay_gio';
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
            $this->base->index();
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
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
        $date = date('d-m-Y');
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $arr_value = $request->all();
            if (count($arr_value) > 0) {
                $validator = Validator::make($arr_value, [
                    self::ngay_gio => 'required',
                    'muc_khuyen_mai' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()->all()], 400);
                }
                $time = strtotime($arr_value[self::ngay_gio]);
                $ngay = date('Y-m-d', $time);
                if (date('d-m-Y', $time) < $date) {
                    return response()->json(['error' => 'Ngày không hợp lệ. Ngày nhập phải lớn hơn ngày hiện tại'], 400);
                }
                $ngay_km = DB::table(self::table)->whereDate(self::ngay_gio, '=', date('Y-m-d', $time))->get();
                if (count($ngay_km) > 0) {
                    return response()->json(['error' => 'Dữ liệu đã tồn tại'], 400);
                }
                if ((int)$arr_value['muc_khuyen_mai'] < 0 || (int)$arr_value['muc_khuyen_mai'] > 100) {
                    return response()->json(['error' => 'Mức khuyến mãi không hợp lệ'], 400);
                }
            } else {
                return response()->json(['error' => 'Thêm mới thất bại. Không có dữ liệu'], 400);
            }

            DB::table(self::table)->insert([self::ngay_gio => $arr_value[self::ngay_gio]]);
            $products = DB::table(SanPhamController::table)->where(self::isActive, '=', true)->get();
            $ma_ngay_km = DB::table(self::table)->select(self::id)
                ->whereDate(self::ngay_gio, '=', $ngay)
                ->where(self::isActive, '=', true)->first();
            foreach ($products as $product) {
                DB::table(KhuyenMaiSanPhamController::table)
                    ->insert([KhuyenMaiSanPhamController::ma_san_pham => $product->ma_san_pham,
                        KhuyenMaiSanPhamController::ma_ngay_khuyen_mai => $ma_ngay_km->ma_ngay_khuyen_mai,
                        KhuyenMaiSanPhamController::muc_khuyen_mai => $arr_value['muc_khuyen_mai']]);
            }
            return response()->json(['success' => "Thêm mới thành công"], 201);
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
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $this->base->show($id);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
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
            $this->base->destroy($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
        }
    }
}

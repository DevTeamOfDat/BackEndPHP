<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DacTrungSanPhamController extends Controller
{
    private $base;
    const table = 'dac_trung_san_phams';
    const id = 'id';
    const loai_dac_trung = 'loai_dac_trung';
    const ma_san_pham = 'ma_san_pham';
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
                ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
                ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
                ->get();
            $code = 200;
//        switch ($query) {
//            case "all":
//                $objs = DB::table(self::table)
//                    ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
//                    ->get();
//                $code = 200;
//                break;
//            case "active":
//                $objs = DB::table(self::table)
//                    ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
//                    ->where(self::table . '.' . self::isActive, '=', true)->get();
//                $code = 200;
//                break;
//            case "inactive":
//                $objs = DB::table(self::table)
//                    ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
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
                                self::loai_dac_trung => 'required',
                                self::ma_san_pham => 'required',
                            ]);
                            if ($validator->fails()) {
                                return response()->json(['error' => $validator->errors()->all()], 200);
                            }
                        }
                    } else {
                        return response()->json(['error' => 'Thêm mới thất bại. Không có dữ liệu'], 200);
                    }
                } else {
                    $arr_value = $request->all();
                    if (count($arr_value) > 0) {
                        $validator = Validator::make($arr_value, [
                            self::loai_dac_trung => 'required',
                            self::ma_san_pham => 'required',
                        ]);
                        if ($validator->fails()) {
                            return response()->json(['error' => $validator->errors()->all()], 200);
                        }
                    } else {
                        return response()->json(['error' => 'Thêm mới thất bại. Không có dữ liệu'], 200);
                    }
                }
            } catch (\Throwable $e) {
                return response()->json(['error' => $e], 500);
            }

            $params = [];
            $params[self::loai_dac_trung] = $request->loai_dac_trung;
            $params[self::ma_san_pham] = $request->ma_san_pham;
            if ($request->so_luong) {
                $sl = DB::table(self::table)
                    ->where(self::table . '.' . self::ma_san_pham, '=', $request->ma_san_pham)
                    ->where(self::table . '.' . self::loai_dac_trung, '=', $request->loai_dac_trung)
                    ->select(self::table . '.' . self::so_luong)
                    ->get();
                $params[self::so_luong] = $request->so_luong + $sl;
            }
            if (DB::table(self::table)->insert($params)) {
                return response()->json(['success' => "Thêm mới thành công"], 201);
            } else {
                return response()->json(['error' => 'Thêm mới thất bại'], 200);
            }
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
        //get theo masp
//        $user = auth()->user();
//        $loai_tk = $user->loai_tai_khoan;
//        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
        $obj = DB::table(self::table)
            ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
            ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
            ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
            ->where(self::table . '.' . self::ma_san_pham, '=', $id)
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
//        $user = auth()->user();
//        $loai_tk = $user->loai_tai_khoan;
//        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {

        $params = [];
        if ($request->so_luong) {
            $sl = DB::table(self::table)
                ->where(self::id, '=', $id)
                ->select(self::table . '.' . self::so_luong)
                ->get();
            $params[self::so_luong] = $sl - $request->so_luong;
        }
        DB::table(self::table)->where(self::id, '=', $id)->update($params);
        $obj = DB::table(self::table)->where(self::id, '=', $id)->get();
        return response()->json(['data' => $obj], 200);
//        } else {
//            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
//        }
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

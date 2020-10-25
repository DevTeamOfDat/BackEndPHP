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
            return response()->json($objs, $code);
        } else {
            return response()->json('Tài khoản không đủ quyền truy cập', 200);
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
            $validator = Validator::make($request->all(), [
                self::loai_dac_trung => 'required',
                self::ma_san_pham => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 200);
            }

            $this->base->store($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json('Tài khoản không đủ quyền truy cập', 200);
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
                ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
                ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
                ->where(self::table . '.' . self::id, '=', $id)
                ->get();
            if ($obj) {
                return response()->json($obj, 200);
            } else {
                return response()->json('Không tìm thấy', 200);
            }
        } else {
            return response()->json('Tài khoản không đủ quyền truy cập', 200);
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
            return response()->json('Tài khoản không đủ quyền truy cập', 200);
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
            return response()->json('Tài khoản không đủ quyền truy cập', 200);
        }
    }
}

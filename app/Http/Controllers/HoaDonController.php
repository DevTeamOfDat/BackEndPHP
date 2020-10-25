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
    const ngay_lap = 'ngay_lap';
    const loai_don = 'loai_don';
    const trang_thai = 'trang_thai';
    const tong_tien = 'tong_tien';
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
                ->join(TaiKhoanController::table . ' as nvs', self::table . '.' . self::ma_nv, '=', 'nvs.' . TaiKhoanController::id)
                ->join(TaiKhoanController::table . ' as khs', self::table . '.' . self::ma_kh, '=', 'khs.' . TaiKhoanController::id)
                ->select(self::table . '.*', 'nvs.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien', 'khs.' . TaiKhoanController::ho_ten . ' as ten_khach_hang')
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
                self::ma_nv => 'required',
                self::ma_kh => 'required',
                self::ngay_lap => 'required',
                self::loai_don => 'required',
                self::trang_thai => 'required',
                self::tong_tien => 'required',
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
            $this->base->show($id);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
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
            $hd = DB::table(self::table)->where(self::id, '=', $id)->first();
            if ($hd->trang_thai == false && $request->get(self::trang_thai) == true) {
                DB::table(self::table)->where(self::id, '=', $id)->update([self::trang_thai => true]);
                return response()->json(['message' => 'Cập nhật thành công'], 200);
            } else {
                return response()->json(['message' => 'Cập nhật thất bại'], 200);
            }
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

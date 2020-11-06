<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PhieuNhapController extends Controller
{
    private $base;
    const table = 'phieu_nhaps';
    const id = 'ma_phieu_nhap';
    const ma_nhan_vien = 'ma_nhan_vien';
    const ma_nha_cung_cap = 'ma_nha_cung_cap';
    const ngay_nhap = 'ngay_nhap';
    const ghi_chu = 'ghi_chu';
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
                ->join(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', self::table . '.' . self::ma_nha_cung_cap)
                ->join(TaiKhoanController::table, self::table . '.' . self::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                ->select(self::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                ->get();
            $code = 200;
//        switch ($query) {
//            case "all":
//                $objs = DB::table(self::table)
//                    ->join(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', self::table . '.' . self::ma_nha_cung_cap)
//                    ->join(TaiKhoanController::table, self::table . '.' . self::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
//                    ->select('phieu_nhaps.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
//                    ->get();
//                $code = 200;
//                break;
//            case "active":
//                $objs = DB::table(self::table)
//                    ->join(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', self::table . '.' . self::ma_nha_cung_cap)
//                    ->join(TaiKhoanController::table, self::table . '.' . self::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
//                    ->select('phieu_nhaps.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
//                    ->where(self::table . '.' . self::isActive, '=', true)->get();
//                $code = 200;
//                break;
//            case "inactive":
//                $objs = DB::table(self::table)
//                    ->join(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', self::table . '.' . self::ma_nha_cung_cap)
//                    ->join(TaiKhoanController::table, self::table . '.' . self::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
//                    ->select('phieu_nhaps.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
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
        date_default_timezone_set(BaseController::timezone);
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $validator = Validator::make($request->all(), [
                self::ma_nha_cung_cap => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 200);
            }

            $obj = [];
            $obj[self::ma_nhan_vien] = $user->ma_tai_khoan;
            $obj[self::ma_nha_cung_cap] = $request->ma_nha_cung_cap;
            $obj[self::ngay_nhap] = date('Y-m-d');
            if ($request->ghi_chu) {
                $obj[self::ghi_chu] = $request->ghi_chu;
            }

            DB::table(self::table)->insert($obj);
            return response()->json(['success' => "Thêm mới thành công"], 201);
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
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            $obj = DB::table(self::table)
                ->join(NhaCungCapController::table, NhaCungCapController::table . '.' . NhaCungCapController::id, '=', self::table . '.' . self::ma_nha_cung_cap)
                ->join(TaiKhoanController::table, self::table . '.' . self::ma_nhan_vien, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
                ->select(self::table . '.*', NhaCungCapController::table . '.' . NhaCungCapController::ten . ' as ten_nha_cung_cap', TaiKhoanController::table . '.' . TaiKhoanController::ho_ten . ' as ten_nhan_vien')
                ->where(self::table . '.' . self::id, '=', $id)
                ->get();
//            $listCouponDetail = DB::table(ChiTietPhieuNhapController::table)
//                ->join(SanPhamController::table, ChiTietPhieuNhapController::table . '.' . ChiTietPhieuNhapController::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                ->select(ChiTietPhieuNhapController::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
//                ->where(ChiTietPhieuNhapController::ma_phieu_nhap, '=', $id)
//                ->get();
            if ($obj) {
                return response()->json([
                    'data' => $obj
                ], 200);
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
            $pn = DB::table(self::table)->where(self::id, '=', $id)->first();
            if (DB::table(self::table)->where(self::id, '=', $id)->update($request->all())) {
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
            try {
                if ($listId = $request->get(BaseController::listId)) {
                    DB::table(ChiTietPhieuNhapController::table)->whereIn(ChiTietPhieuNhapController::ma_phieu_nhap, $listId)
                        ->where(ChiTietPhieuNhapController::isActive, '=', true)
                        ->update([ChiTietPhieuNhapController::isActive => false]);
                } else {
                    $id = $request->get(BaseController::key_id);
                    DB::table(ChiTietPhieuNhapController::table)->where(ChiTietPhieuNhapController::ma_phieu_nhap, $id)
                        ->where(ChiTietPhieuNhapController::isActive, '=', true)
                        ->update([ChiTietPhieuNhapController::isActive => false]);
                }
            } catch (\Throwable $e) {
                report($e);
            }
            $this->base->destroy($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
        }
    }
}

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
    const danh_sach_loai_dac_trung = 'danh_sach_loai_dac_trung';
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
            foreach ($objs as $obj){
                $list_speciality_id = DB::table(self::table)
                    ->select(self::danh_sach_loai_dac_trung)
                    ->where(self::table . '.' . self::id, '=', $obj->id)
                    ->get();

                foreach ($list_speciality_id as $index => $speciality_id) {
                    $speciality = $speciality_id->danh_sach_loai_dac_trung;
                    $speciality = substr($speciality, 1, strlen($speciality) - 2);
                    $arr = explode(',', $speciality);
                    $str = '';
                    foreach ($arr as $item) {
                        $ten_dac_trung = DB::table(DacTrungController::table)
                            ->select(DacTrungController::ten_dac_trung)
                            ->where(DacTrungController::id, '=', $item)
                            ->get();
                        $str = $str . $ten_dac_trung[0]->ten_dac_trung . ', ';
                    }
                    $str = substr($str, 0, strlen($str) - 2);
                    $obj->ten_dac_trung = $str;
                }
            }
            $code = 200;
            return response()->json(['data' => $objs], $code);
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
        $user = auth()->user();
        $loai_tk = $user->loai_tai_khoan;
        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
            try {
                $arr_value = $request->all();
                if (count($arr_value) > 0) {
                    $validator = Validator::make($arr_value, [
                        self::ma_phieu_nhap => 'required',
                        self::ma_san_pham => 'required',
                        self::danh_sach_loai_dac_trung => 'required',
                        self::gia_nhap => 'required',
                        self::so_luong => 'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['error' => $validator->errors()->all()], 400);
                    }
                    if ($arr_value[self::gia_nhap] < 1) {
                        return response()->json(['error' => 'Giá nhập phải lớn hơn 0'], 400);
                    }
                    if ($arr_value[self::so_luong] < 1) {
                        return response()->json(['error' => 'Số lượng phải lớn hơn 0'], 400);
                    }
                    $str = '[';
                    foreach ($arr_value[self::danh_sach_loai_dac_trung] as $item) {
                        $str = $str . $item . ',';
                    }
                    $str = substr($str, 0, strlen($str) - 1);
                    $str = $str . ']';
                    $arr_value[self::danh_sach_loai_dac_trung] = $str;
                    $obj = DB::table(self::table)
                        ->select(self::table . '.*')
                        ->where(self::ma_san_pham, '=', $arr_value[self::ma_san_pham])
                        ->where(self::ma_phieu_nhap, '=', $arr_value[self::ma_phieu_nhap])
                        ->where(self::danh_sach_loai_dac_trung, '=', $str)
                        ->where(self::isActive, '=', true)->get();
                    if (count($obj) > 0) {
                        return response()->json(['error' => 'Thêm mới thất bại. Có 1 row đã tồn tại mã phiếu nhập và mã sản phẩm'], 400);
                    }
                    DB::table(self::table)->insert($arr_value);
                    return response()->json(['success' => 'Thêm mới thành công'], 201);
                } else {
                    return response()->json(['error' => 'Thêm mới thất bại. Không có dữ liệu'], 400);
                }
            } catch (\Throwable $e) {
                return response()->json(['error' => $e], 500);
            }
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
        $obj = DB::table(self::table)
            ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
            ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham)
            ->where(self::table . '.' . self::id, '=', $id)
            ->get();
        $list_speciality_id = DB::table(self::table)
            ->select(self::danh_sach_loai_dac_trung)
            ->where(self::table . '.' . self::id, '=', $id)
            ->get();

        foreach ($list_speciality_id as $index => $speciality_id) {
            $speciality = $speciality_id->danh_sach_loai_dac_trung;
            $speciality = substr($speciality, 1, strlen($speciality) - 2);
            $arr = explode(',', $speciality);
            foreach ($arr as $item) {
                $dac_trung = DB::table(DacTrungController::table)
                    ->select(DacTrungController::ten_dac_trung, DacTrungController::mo_ta)
                    ->where(DacTrungController::id, '=', $item)
                    ->get();
                if($dac_trung[0]->mo_ta == "màu"){
                    $obj[$index]->mau = $dac_trung[0]->ten_dac_trung;
                } elseif ($dac_trung[0]->mo_ta == "size"){
                    $obj[$index]->size = $dac_trung[0]->ten_dac_trung;
                }
            }
        }
        if ($obj) {
            return response()->json(['data' => $obj], 200);
        } else {
            return response()->json(['error' => 'Không tìm thấy'], 200);
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

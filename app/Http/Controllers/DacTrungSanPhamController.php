<?php

namespace App\Http\Controllers;

use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DacTrungSanPhamController extends Controller
{
    private $base;
    const table = 'dac_trung_san_phams';
    const id = 'id';
    const danh_sach_loai_dac_trung = 'danh_sach_loai_dac_trung';
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
                                self::danh_sach_loai_dac_trung => 'required',
                                self::ma_san_pham => 'required',
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
                            self::danh_sach_loai_dac_trung => 'required',
                            self::ma_san_pham => 'required',
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

            $str = '[';
            $params = [];
            foreach ($request->danh_sach_loai_dac_trung as $item) {
                $str = $str . $item . ',';
            }
            $str = substr($str, 0, strlen($str) - 1);
            $str = $str . ']';
            $params[self::danh_sach_loai_dac_trung] = $str;
            $params[self::ma_san_pham] = $request->ma_san_pham;
            if ($request->so_luong) {
                $sl = DB::table(self::table)
                    ->where(self::table . '.' . self::ma_san_pham, '=', $request->ma_san_pham)
                    ->where(self::table . '.' . self::danh_sach_loai_dac_trung, '=', $str)
                    ->select(self::table . '.' . self::so_luong)
                    ->get();
                if (count($sl) > 0) {
                    $params[self::so_luong] = $request->so_luong + $sl[0]->so_luong;
                    if (DB::table(self::table)->update($params)) {
                        return response()->json(['success' => "Thêm mới thành công"], 201);
                    } else {
                        return response()->json(['error' => 'Thêm mới thất bại'], 400);
                    }
                } else {
                    $params[self::so_luong] = $request->so_luong;
                    if (DB::table(self::table)->insert($params)) {
                        return response()->json(['success' => "Thêm mới thành công"], 201);
                    } else {
                        return response()->json(['error' => 'Thêm mới thất bại'], 400);
                    }
                }
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
            ->where(self::table . '.' . self::ma_san_pham, '=', $id)
            ->get();
        $list_speciality_id = DB::table(self::table)
            ->select(self::danh_sach_loai_dac_trung)
            ->where(self::table . '.' . self::ma_san_pham, '=', $id)
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
            $obj[$index]->ten_dac_trung = $str;
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
        $params = [];
        if ($request->so_luong) {
            $sl = DB::table(self::table)
                ->where(self::id, '=', $id)
                ->select(self::table . '.' . self::so_luong)
                ->get();
            $params[self::so_luong] = $sl[0]->so_luong - $request->so_luong;
        }
        DB::table(self::table)->where(self::id, '=', $id)->update($params);
        $obj = DB::table(self::table)->where(self::id, '=', $id)->get();
        return response()->json(['data' => $obj], 201);
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

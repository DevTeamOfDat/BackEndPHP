<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NhanXetController extends Controller
{
    private $base;
    const table = 'nhan_xets';
    const id = 'ma_nhan_xet';
    const ma_san_pham = 'ma_san_pham';
    const ma_khach_hang = 'ma_khach_hang';
    const binh_luan = 'binh_luan';
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
//        $user = auth()->user();
//        $loai_tk = $user->loai_tai_khoan;
//        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
        $objs = null;
        $code = null;
        $objs = DB::table(self::table)
            ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
            ->join(TaiKhoanController::table, self::table . '.' . self::ma_khach_hang, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
            ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, TaiKhoanController::table . '.' . TaiKhoanController::ho_ten)
            ->get();
        $code = 200;
//        switch ($query) {
//            case "all":
//                $objs = DB::table(self::table)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->join(TaiKhoanController::table, self::table . '.' . self::ma_khach_hang, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, TaiKhoanController::table . '.' . TaiKhoanController::ho_ten)
//                    ->get();
//                $code = 200;
//                break;
//            case "active":
//                $objs = DB::table(self::table)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->join(TaiKhoanController::table, self::table . '.' . self::ma_khach_hang, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, TaiKhoanController::table . '.' . TaiKhoanController::ho_ten)
//                    ->where(self::table . '.' . self::isActive, '=', true)->get();
//                $code = 200;
//                break;
//            case "inactive":
//                $objs = DB::table(self::table)
//                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
//                    ->join(TaiKhoanController::table, self::table . '.' . self::ma_khach_hang, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
//                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, TaiKhoanController::table . '.' . TaiKhoanController::ho_ten)
//                    ->where(self::table . '.' . self::isActive, '=', false)->get();
//                $code = 200;
//                break;
//            default:
//                $objs = "Không tìm thấy";
//                $code = 200;
//                break;
//        }
        return response()->json(['data' => $objs], $code);
//        } else {
//            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
//        }
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
//        $user = auth()->user();
//        $loai_tk = $user->loai_tai_khoan;
//        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
        $validator = Validator::make($request->all(), [
            self::ma_khach_hang => 'required',
            self::binh_luan => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 200);
        }

        $this->base->store($request);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
//        } else {
//            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 200);
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        $user = auth()->user();
//        $loai_tk = $user->loai_tai_khoan;
//        if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT) {
        $obj = DB::table(self::table)
            ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
            ->join(TaiKhoanController::table, self::table . '.' . self::ma_khach_hang, '=', TaiKhoanController::table . '.' . TaiKhoanController::id)
            ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, TaiKhoanController::table . '.' . TaiKhoanController::ho_ten)
            ->where(self::table . '.' . self::id, '=', $id)
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
        $user = auth()->user();
        $ma_tk = $user->ma_tai_khoan;
        $ma_kh = DB::table(self::table)->where(self::id, '=', $id)->get(self::ma_khach_hang)->first();
        if ($ma_kh == $ma_tk) {
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
        $ma_tk = $user->ma_tai_khoan;
        try {
            if ($listId = $request->get(BaseController::listId)) {
                if (count($listId) > 0) {
                    foreach ($listId as $id) {
                        $ma_kh = DB::table(self::table)->where(self::id, '=', $id)->get(self::ma_khach_hang);
                        if ($loai_tk != TaiKhoanController::NV && $loai_tk != TaiKhoanController::QT && $ma_kh != $ma_tk) {
                            return response()->json(['error' => 'Xóa thất bại. Bạn không được phép xóa nhận xét của người khác'], 200);
                        }
                    }
                    DB::table(self::table)->whereIn(self::id, $listId)->update([self::isActive => false]);
                    return response()->json(['success' => 'Xóa thành công'], 200);
                } else {
                    return response()->json(['error' => 'Xóa thất bại. Không có dữ liệu'], 200);
                }
            } else {
                $id = $request->get(BaseController::key_id);
                $ma_kh = DB::table(self::table)->where(self::id, '=', $id)->get(self::ma_khach_hang)->first();
                if ($loai_tk == TaiKhoanController::NV || $loai_tk == TaiKhoanController::QT || $ma_kh == $ma_tk) {
                    if ($obj = DB::table(self::table)->where(self::id, '=', $id)->update([self::isActive => false])) {
                        return response()->json(['success' => 'Xóa thành công'], 200);
                    } else {
                        return response()->json(['error' => 'Xóa thất bại'], 200);
                    }
                } else {
                    return response()->json(['error' => 'Tài khoản không đủ quyền để thực hiện thao tác này'], 200);
                }
            }
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['error' => $e], 500);
        }
    }

}

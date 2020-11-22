<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Hash;

class TaiKhoanController extends Controller
{
    private $base;
    const table = 'tai_khoans';
    const id = 'ma_tai_khoan';
    const email = 'email';
    const email_verified_at = 'email_verified_at';
    const mat_khau = 'mat_khau';
    const ho_ten = 'ho_ten';
    const dia_chi = 'dia_chi';
    const so_dien_thoai = 'so_dien_thoai';
    const hinh_anh = 'hinh_anh';
    const loai_tai_khoan = 'loai_tai_khoan';
    const isActive = 'isActive';
    const NV = 'NV';
    const QT = 'QT';

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
        if ($loai_tk == self::NV || $loai_tk == self::QT) {
            $objs = DB::table(self::table)
                ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
                ->select(self::id, self::ho_ten, self::email, self::dia_chi, self::so_dien_thoai, self::loai_tai_khoan, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta, self::hinh_anh, self::table . '.' . self::isActive)
                ->where(self::table . '.' . self::loai_tai_khoan, '=', 'KH')
                ->orWhere(self::table . '.' . self::loai_tai_khoan, '=', 'NV')
                ->get();
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
        if ($loai_tk == self::NV || $loai_tk == self::QT) {
            if ($listObj = $request->get(BaseController::listObj)) {
                $count = count($listObj);
                if ($count > 0) {
                    foreach ($listObj as $obj) {
                        $validator = Validator::make($obj, [
                            self::email => 'required|email',
                            self::mat_khau => 'required|min:8',
                            self::ho_ten => 'required',
                            self::so_dien_thoai => 'required',
                            self::loai_tai_khoan => 'required',
                        ]);
                        if ($validator->fails()) {
                            return response()->json(['error' => $validator->errors()->all()], 400);
                        }
                    }
                }
            } else {
                $validator = Validator::make($request->all(), [
                    self::email => 'required|email',
                    self::mat_khau => 'required|min:8',
                    self::ho_ten => 'required',
                    self::so_dien_thoai => 'required',
                    self::loai_tai_khoan => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()->all()], 400);
                }
            }
            $array = [];
            $array[self::ho_ten] = $request->ho_ten;
            $array[self::email] = $request->email;
            $array[self::email_verified_at] = date('Y-m-d');
            $array[self::mat_khau] = bcrypt($request->mat_khau);
            if ($request->dia_chi != null) {
                $array[self::dia_chi] = $request->dia_chi;
            }
            $array[self::so_dien_thoai] = $request->so_dien_thoai;
            $array[self::so_dien_thoai] = $request->so_dien_thoai;
            if ($request->hinh_anh != null) {
                $array[self::hinh_anh] = $request->hinh_anh;
            }
            $array[self::loai_tai_khoan] = $request->loai_tai_khoan;

            DB::table(self::table)->insert($array);

            $email = $request->email;

            $tk = TaiKhoan::where(self::email, $email)->first();

            $token = $tk->createToken('WebsiteBanGiayPHP')->accessToken;
            return response()->json(['token' => $token, 'data' => $tk], 201);
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
        if ($loai_tk == self::NV || $loai_tk == self::QT) {
            $client = DB::table(self::table)
                ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
                ->select(self::id, self::ho_ten, self::email, self::dia_chi, self::so_dien_thoai, self::loai_tai_khoan, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta, self::hinh_anh, self::table . '.' . self::isActive)
                ->where(self::table . '.' . self::id, '=', $id)->first();
            if ($client) {
                return response()->json(['data' => $client], 200);
            } else {
                return response()->json(['error' => "Không tìm thấy"], 200);
            }
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            self::email => 'required|email'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        $tk = DB::table(self::table)->where(self::email, $request->email)->get();
        if (count($tk) == 0) {
            return response()->json(['error' => 'Email không chính xác'], 400);
        }
        $tk = $tk[0];
        $array = [];
        $array[self::email] = $request->email;
        if ($request->ho_ten != null) {
            $array[self::ho_ten] = $request->ho_ten;
        }
        if ($request->dia_chi != null) {
            $array[self::dia_chi] = $request->dia_chi;
        }
        if ($request->so_dien_thoai != null) {
            $array[self::so_dien_thoai] = $request->so_dien_thoai;
        }
        if ($request->hinh_anh != null) {
            $array[self::hinh_anh] = $request->hinh_anh;
        }
        if ($request->mat_khau_moi != null && !Hash::check($request->mat_khau_cu, $tk->mat_khau)) {
            return response()->json(['error' => 'Chỉnh sửa thất bại. Mật khẩu cũ không chính xác'], 400);
        } elseif ($request->mat_khau_moi != null && $request->mat_khau_cu != null && $request->mat_khau_moi == $request->mat_khau_cu) {
            return response()->json(['error' => 'Chỉnh sửa thất bại. Mật khẩu mới phải khác mật khẩu cũ'], 400);
        } elseif ($request->mat_khau_moi != null && strlen($request->mat_khau_moi) < 8) {
            return response()->json(['error' => 'Chỉnh sửa thất bại. Mật khẩu mới phải nhiều hơn 8 ký tự'], 400);
        } elseif ($request->mat_khau_moi != null && $request->mat_khau_cu != null && $request->mat_khau_moi != $request->mat_khau_cu && Hash::check($request->mat_khau_cu, $tk->mat_khau)) {
            $array[self::mat_khau] = bcrypt($request->mat_khau_moi);
        }
        if (count($array) == 1) {
            return response()->json(['error' => 'Chỉnh sửa thất bại. Thiếu thông tin'], 400);
        }
        DB::table(self::table)->where(self::email, $request->email)->update($array);
        $tk = TaiKhoan::where(self::email, $request->email)->first();
        if ($request->mat_khau_moi != null) {
            $token = $tk->createToken('WebsiteBanGiayPHP')->accessToken;
            return response()->json(['token' => $token, 'data' => $tk], 200);
        }
        return response()->json(['data' => $tk], 200);
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
        if ($loai_tk == self::NV || $loai_tk == self::QT) {
            try {
                if ($listId = $request->get(BaseController::listId)) {
                    if (count($listId) > 0) {
                        foreach ($listId as $id) {
                            if (DB::table(self::table)->where(self::table . '.' . self::loai_tai_khoan, '=', 'KH')
                                ->where(self::table . '.' . self::id, '=', $id)->get()) {
                                return response()->json(['error' => 'Xóa thất bại. Không thể xóa tài khoản khách hàng'], 403);
                            }
                        }
                    } else {
                        return response()->json(['error' => 'Xóa thất bại. Không có dữ liệu'], 400);
                    }
                } else {
                    $id = $request->get(BaseController::key_id);
                    if (DB::table(self::table)->where(self::table . '.' . self::loai_tai_khoan, '=', 'KH')
                        ->where(self::table . '.' . self::id, '=', $id)->get()) {
                        return response()->json(['error' => 'Xóa thất bại. Không thể xóa tài khoản khách hàng'], 403);
                    }
                }
            } catch (\Throwable $e) {
                report($e);
                return response()->json(['error' => $e], 500);
            }
            $this->base->destroy($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json(['error' => 'Tài khoản không đủ quyền truy cập'], 403);
        }
    }

    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        date_default_timezone_set(BaseController::timezone);
        $validator = Validator::make($request->all(), [
            self::ho_ten => 'required',
            self::email => 'required|email',
            self::mat_khau => 'required|min:8',
            self::dia_chi => 'required',
            self::so_dien_thoai => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $array = [];
        $array[self::ho_ten] = $request->ho_ten;
        $array[self::email] = $request->email;
        $array[self::email_verified_at] = date('Y-m-d');
        $array[self::mat_khau] = bcrypt($request->mat_khau);
        $array[self::dia_chi] = $request->dia_chi;
        $array[self::so_dien_thoai] = $request->so_dien_thoai;
        if ($request->hinh_anh != null) {
            $array[self::hinh_anh] = $request->hinh_anh;
        }
        DB::table(self::table)->insert($array);

        $email = $request->email;

        $tk = TaiKhoan::where(self::email, $email)->first();

        $token = $tk->createToken('WebsiteBanGiayPHP')->accessToken;

        return response()->json(['token' => $token, 'data' => $tk], 201);
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            self::email => 'required|email',
            self::mat_khau => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $email = $request->email;

        $tk = TaiKhoan::where(self::email, $email)->first();

        if ($tk) {
            if (Hash::check($request->mat_khau, $tk->mat_khau)) {
                $token = $tk->createToken('WebsiteBanGiayPHP')->accessToken;
                return response()->json(['token' => $token, 'data' => $tk], 200);
            } else {
                return response()->json(['error' => 'Password mismatch'], 400);
            }
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function userInfo()
    {
        $user = auth()->user();
        return response()->json(['data' => $user], 200);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['success' => 'logout success'], 200);
        } else {
            return response()->json(['error' => 'api.something went wrong'], 500);
        }
    }
}

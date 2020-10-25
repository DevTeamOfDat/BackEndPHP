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
                ->select(self::id, self::ho_ten, self::email, self::dia_chi, self::so_dien_thoai, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta, self::hinh_anh, self::table . '.' . self::isActive)
                ->where(self::table . '.' . self::loai_tai_khoan, '=', 'KH')
                ->get();
            $code = 200;
//            switch ($query) {
//                case "all":
//                    $objs = DB::table(self::table)
//                        ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
//                        ->select(self::id, self::ho_ten, self::email, self::dia_chi, self::so_dien_thoai, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta, self::hinh_anh, self::table . '.' . self::isActive)
//                        ->where(self::table . '.' . self::loai_tai_khoan, '=', 'KH')
//                        ->get();
//                    $code = 200;
//                    break;
//                case "active":
//                    $objs = DB::table(self::table)
//                        ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
//                        ->select(self::id, self::ho_ten, self::email, self::dia_chi, self::so_dien_thoai, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta, self::hinh_anh, self::table . '.' . self::isActive)
//                        ->where(self::table . '.' . self::isActive, '=', true)
//                        ->where(self::table . '.' . self::loai_tai_khoan, '=', 'KH')->get();
//                    $code = 200;
//                    break;
//                case "inactive":
//                    $objs = DB::table(self::table)
//                        ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
//                        ->select(self::id, self::ho_ten, self::email, self::dia_chi, self::so_dien_thoai, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta, self::hinh_anh, self::table . '.' . self::isActive)
//                        ->where(self::table . '.' . self::isActive, '=', false)
//                        ->where(self::table . '.' . self::loai_tai_khoan, '=', 'KH')->get();
//                    $code = 200;
//                    break;
//                default:
//                    $objs = "Không tìm thấy";
//                    $code = 200;
//                    break;
//            }
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
                            self::ho_ten => 'required|min:8',
                            self::so_dien_thoai => 'required|min:10',
                            self::loai_tai_khoan => 'required',
                        ]);
                        if ($validator->fails()) {
                            return response()->json(['error' => $validator->errors()->all()], 200);
                        }
                    }
                }
            } else {
                $validator = Validator::make($request->all(), [
                    self::email => 'required|email',
                    self::mat_khau => 'required|min:8',
                    self::ho_ten => 'required|min:8',
                    self::so_dien_thoai => 'required|min:10',
                    self::loai_tai_khoan => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()->all()], 200);
                }
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
        if ($loai_tk == self::NV || $loai_tk == self::QT) {
            $client = DB::table(self::table)
                ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
                ->select(self::id, self::ho_ten, self::email, self::dia_chi, self::so_dien_thoai, LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta, self::hinh_anh, self::table . '.' . self::isActive)
                ->where(self::table . '.' . self::id, '=', $id)->first();
            if ($client) {
                return response()->json($client, 200);
            } else {
                return response()->json("Không tìm thấy", 200);
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
        if ($loai_tk == self::NV || $loai_tk == self::QT) {
            $validator = Validator::make($request->all(), [
                self::email => 'required|email',
                self::mat_khau => 'required|min:8',
                self::ho_ten => 'required|min:8',
                self::so_dien_thoai => 'required|min:10',
                self::loai_tai_khoan => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 200);
            }
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
        if ($loai_tk == self::NV || $loai_tk == self::QT) {
            $this->base->destroy($request);
            return response()->json($this->base->getMessage(), $this->base->getStatus());
        } else {
            return response()->json('Tài khoản không đủ quyền truy cập', 200);
        }
    }

    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            self::ho_ten => 'required|min:8',
            self::email => 'required|email',
            self::mat_khau => 'required|min:8',
            self::dia_chi => 'required|min:8',
            self::so_dien_thoai => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 200);
        }

        DB::table(self::table)->insert([
            self::ho_ten => $request->ho_ten,
            self::email => $request->email,
            self::email_verified_at => now(),
            self::mat_khau => bcrypt($request->mat_khau),
            self::dia_chi => $request->dia_chi,
            self::so_dien_thoai => $request->so_dien_thoai,
        ]);

        $email = $request->email;

        $tk = TaiKhoan::where(self::email, $email)->first();

        $token = $tk->createToken('WebsiteBanGiayPHP')->accessToken;

        return response()->json(['token' => $token, 'account' => $tk], 200);
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            self::email => 'required|email',
            self::mat_khau => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 200);
        }

        $email = $request->email;

        $tk = TaiKhoan::where(self::email, $email)->first();

        if ($tk) {
            if (Hash::check($request->mat_khau, $tk->mat_khau)) {
                $token = $tk->createToken('WebsiteBanGiayPHP')->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'Password mismatch'], 200);
            }
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function userInfo()
    {
        $user = auth()->user();
        return response()->json(['account' => $user], 200);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['success' =>'logout_success'],200);
        }else{
            return response()->json(['error' =>'api.something_went_wrong'], 500);
        }
    }
}

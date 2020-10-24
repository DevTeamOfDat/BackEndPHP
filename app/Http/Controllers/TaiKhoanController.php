<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;

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
    public function index($query)
    {
//        $this->base->index($query);
//        return response()->json($this->base->getMessage(), $this->base->getStatus());

        $objs = null;
        $code = null;
        switch ($query) {
            case "all":
                $objs = DB::table(self::table)
                    ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
                    ->select(self::table . '.*', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta)
                    ->get();
                $code = 200;
                break;
            case "active":
                $objs = DB::table(self::table)
                    ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
                    ->select(self::table . '.*', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta)
                    ->where(self::table . '.' . self::isActive, '=', true)->get();
                $code = 200;
                break;
            case "inactive":
                $objs = DB::table(self::table)
                    ->join(LoaiTaiKhoanController::table, self::table . '.' . self::loai_tai_khoan, '=', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::gia_tri)
                    ->select(self::table . '.*', LoaiTaiKhoanController::table . '.' . LoaiTaiKhoanController::mo_ta)
                    ->where(self::table . '.' . self::isActive, '=', false)->get();
                $code = 200;
                break;
            default:
                $objs = "Không tìm thấy";
                $code = 200;
                break;
        }
        return response()->json($objs, $code);
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
        $this->validate($request, [
            self::email => 'required|email',
            self::mat_khau => 'required|min:8',
            self::ho_ten => 'required|min:8',
            self::so_dien_thoai => 'required|min:10',
            self::loai_tai_khoan => 'required',
        ]);

        $this->base->store($request);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->base->show($id);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
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
        $this->base->update($request, $id);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->base->destroy($request);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
    }

    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'ho_ten' => 'required|min:8',
            'email' => 'required|email',
            'mat_khau' => 'required|min:8',
            'dia_chi' => 'required|min:8',
            'so_dien_thoai' => 'required|min:10',
        ]);

        if ($request->loai_tai_khoan) {
            DB::table(self::table)->insert([
                'ho_ten' => $request->ho_ten,
                'email' => $request->email,
                'email_verified_at' => now(),
                'mat_khau' => bcrypt($request->mat_khau),
                'dia_chi' => $request->dia_chi,
                'so_dien_thoai' => $request->so_dien_thoai,
                'loai_tai_khoan' => $request->loai_tai_khoan,
            ]);
        } else {
            DB::table(self::table)->insert([
                'ho_ten' => $request->ho_ten,
                'email' => $request->email,
                'email_verified_at' => now(),
                'mat_khau' => bcrypt($request->mat_khau),
                'dia_chi' => $request->dia_chi,
                'so_dien_thoai' => $request->so_dien_thoai,
            ]);
        }

        $email = $request->email;

        $tk = TaiKhoan::where('email', $email)->first();

        $token = $tk->createToken('WebsiteBanGiayPHP')->accessToken;

        return response()->json(['token' => $token, 'account' => $tk], 200);
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'mat_khau' => $request->mat_khau,
        ];

        if (auth()->attempt($data, true)) {
            $token = auth()->user()->createToken('WebsiteBanGiayPHP')->accessToken;
            return response()->json(['token' => $token], 200);
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
        auth()->logout();
    }
}

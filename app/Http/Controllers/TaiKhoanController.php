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
        $this->base->index($query);
        return response()->json($this->base->getMessage(), $this->base->getStatus());
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function index($query)
    {
//        $this->base->index($query);
//        return response()->json($this->base->getMessage(), $this->base->getStatus());

        $objs = null;
        $code = null;
        switch ($query) {
            case "all":
                $objs = DB::table(self::table)
                    ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
                    ->get();
                $code = 200;
                break;
            case "active":
                $objs = DB::table(self::table)
                    ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
                    ->where(self::table . '.' . self::isActive, '=', true)->get();
                $code = 200;
                break;
            case "inactive":
                $objs = DB::table(self::table)
                    ->join(DacTrungController::table, self::table . '.' . self::loai_dac_trung, '=', DacTrungController::table . '.' . DacTrungController::id)
                    ->join(SanPhamController::table, self::table . '.' . self::ma_san_pham, '=', SanPhamController::table . '.' . SanPhamController::id)
                    ->select(self::table . '.*', SanPhamController::table . '.' . SanPhamController::ten_san_pham, DacTrungController::table . '.' . DacTrungController::ten_dac_trung)
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
            self::loai_dac_trung => 'required',
            self::ma_san_pham => 'required',
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
}
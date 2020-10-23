<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChiTietHoaDonController extends Controller
{
    private $base;
    const table = 'chi_tiet_hoa_dons';
    const id = 'id';
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
        $this->validate($request, [
            'ma_hoa_don' => 'required',
            'ma_san_pham' => 'required',
            'gia_ban' => 'required',
            'so_luong' => 'required',
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

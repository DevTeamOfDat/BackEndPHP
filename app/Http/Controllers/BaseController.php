<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    private $table;
    private $id;
    private $isActive;
    private $message;
    private $status;
    private $size;
    const listObj = 'listObj';
    const listId = 'listId';
    const key_id = 'id';

    /**
     * BaseController constructor.
     * @param $table
     */
    public function __construct($table, $id, $isActive)
    {
        $this->table = $table;
        $this->id = $id;
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request): void
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $objs = null;
        $code = null;
        $objs = DB::table($this->table)->get();
        $code = 200;
//        switch ($query) {
//            case "all":
//                $objs = DB::table($this->table)->get();
//                $code = 200;
//                break;
//            case "active":
//                $objs = DB::table($this->table)->where($this->isActive, true)->get();
//                $code = 200;
//                break;
//            case "inactive":
//                $objs = DB::table($this->table)->where($this->isActive, false)->get();
//                $code = 200;
//                break;
//            default:
//                $objs = "Không tìm thấy";
//                $code = 200;
//                break;
//        }
        $this->message = ['data' => $objs];
        $this->status = $code;
        try {
            $this->size = count($objs);
        } catch (\Throwable $e) {
            $this->size = null;
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
    public function store($request)
    {
        //
        try {
            if ($listObj = $request->get(self::listObj)) {
                $count = count($listObj);
                if ($count > 0) {
                    foreach ($listObj as $obj) {
                        DB::table($this->table)->insert($obj);
                    }
                    $this->message = ['success' => "Thêm mới thành công"];
                    $this->status = 201;
                } else {
                    $this->message = ['error' => 'Thêm mới thất bại'];
                    $this->status = 200;
                }
            } else {
                $arr_value = $request->all();
                if (count($arr_value) > 0) {
                    DB::table($this->table)->insert($arr_value);
                    $this->message = ['success' => "Thêm mới thành công"];
                    $this->status = 201;
                } else {
                    $this->message = ['error' => 'Thêm mới thất bại'];
                    $this->status = 200;
                }
            }
        } catch (\Throwable $e) {
            $this->message = ['error' => $e];
            $this->status = 500;
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
//        if (count($this->table) == 1) {
        if ($obj = DB::table($this->table)->where($this->id, '=', $id)->get()) {
            $this->message = ['data' => $obj];
            $this->status = 200;
        } else {
            $this->message = ['error' => "Không tìm thấy"];
            $this->status = 200;
        }
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
        if (DB::table($this->table)->where($this->id, '=', $id)->update($request->all())) {
            $obj = DB::table($this->table)->where($this->id, '=', $id)->get();
            $this->message = ['data' => $obj];
            $this->status = 200;
        } else {
            $this->message = ['error' => "Chỉnh sửa thất bại"];
            $this->status = 200;
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
        try {
            if ($listId = $request->get(self::listId)) {
                if (count($listId) > 0 && DB::table($this->table)->whereIn($this->id, $listId)->update([$this->isActive => false])) {
                    $this->message = ['success' => 'Xóa thành công'];
                    $this->status = 200;
                } else {
                    $this->message = ['error' => 'Xóa thất bại'];
                    $this->status = 200;
                }
            } else {
                $id = $request->get(self::key_id);
                if ($obj = DB::table($this->table)->where($this->id, '=', $id)->update([$this->isActive => false])) {
                    $this->message = ['success' => 'Xóa thành công'];
                    $this->status = 200;
                } else {
                    $this->message = ['error' => 'Xóa thất bại'];
                    $this->status = 200;
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->message = ['error' => $e];
            $this->status = 500;
        }
    }
}

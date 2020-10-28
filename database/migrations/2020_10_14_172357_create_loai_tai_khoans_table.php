<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLoaiTaiKhoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_tai_khoans', function (Blueprint $table) {
            $table->id();
            $table->string('gia_tri');
            $table->string('mo_ta')->default('');
            $table->boolean('isActive')->default(true);
        });

        DB::table('loai_tai_khoans')->insert([
            'gia_tri' => 'KH',
            'mo_ta' => 'Khách hàng',
        ]);
        DB::table('loai_tai_khoans')->insert([
            'gia_tri' => 'NV',
            'mo_ta' => 'Nhân viên',
        ]);
        DB::table('loai_tai_khoans')->insert([
            'gia_tri' => 'QT',
            'mo_ta' => 'Quản trị',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loai_tai_khoans');
    }
}

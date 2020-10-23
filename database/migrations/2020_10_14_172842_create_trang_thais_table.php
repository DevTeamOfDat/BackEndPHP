<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrangThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trang_thais', function (Blueprint $table) {
            $table->id();
            $table->string('gia_tri');
            $table->string('mo_ta');
            $table->boolean('isActive')->default(true);
        });

        DB::table('trang_thais')->insert([
            'gia_tri' => 'true',
            'mo_ta' => 'Đã gửi/nhận hàng',
        ]);
        DB::table('trang_thais')->insert([
            'gia_tri' => 'false',
            'mo_ta' => 'Chưa gửi/nhận hàng',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trang_thais');
    }
}

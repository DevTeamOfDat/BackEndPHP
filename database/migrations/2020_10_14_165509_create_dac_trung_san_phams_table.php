<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDacTrungSanPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dac_trung_san_phams', function (Blueprint $table) {
            $table->id();
            $table->string('danh_sach_loai_dac_trung');
            $table->bigInteger('ma_san_pham');
            $table->integer('so_luong')->default(0);
            $table->boolean('isActive')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dac_trung_san_phams');
    }
}

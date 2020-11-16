<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSanPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('san_phams', function (Blueprint $table) {
            $table->id('ma_san_pham');
            $table->bigInteger('ma_thuong_hieu');
            $table->bigInteger('ma_loai_san_pham');
            $table->string('ten_san_pham');
            $table->double('gia_ban', 15, 2)->default(0.00);
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
        Schema::dropIfExists('san_phams');
    }
}

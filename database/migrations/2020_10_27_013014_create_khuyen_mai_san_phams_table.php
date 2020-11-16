<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateKhuyenMaiSanPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khuyen_mai_san_phams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ma_san_pham');
            $table->bigInteger('ma_ngay_khuyen_mai');
            $table->integer('muc_khuyen_mai')->comment('%');
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
        Schema::dropIfExists('khuyen_mai_san_phams');
    }
}

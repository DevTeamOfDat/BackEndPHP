<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiTietHoaDonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->bigInteger('ma_hoa_don');
            $table->bigInteger('ma_san_pham');
            $table->string('danh_sach_loai_dac_trung');
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
        Schema::dropIfExists('chi_tiet_hoa_dons');
    }
}

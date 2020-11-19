<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateHoaDonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id('ma_hoa_don');
            $table->bigInteger('ma_nhan_vien')->nullable();
            $table->bigInteger('ma_khach_hang')->nullable();
            $table->bigInteger('ma_voucher')->nullable();
            $table->date('ngay_lap')->nullable();
            $table->boolean('loai_don')->default(true);
            $table->boolean('trang_thai')->default(true);
            $table->double('tong_tien', 15, 2)->default(0.00);
            $table->double('thanh_tien', 15, 2)->default(0.00);
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
        Schema::dropIfExists('hoa_dons');
    }
}

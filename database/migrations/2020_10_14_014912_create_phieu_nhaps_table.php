<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePhieuNhapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieu_nhaps', function (Blueprint $table) {
            $table->id('ma_phieu_nhap');
            $table->bigInteger('ma_nhan_vien');
            $table->bigInteger('ma_nha_cung_cap');
            $table->date('ngay_nhap')->nullable();
            $table->double('tong_tien', 15, 2)->default(0.00);
            $table->string('ghi_chu')->nullable();
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
        Schema::dropIfExists('phieu_nhaps');
    }
}

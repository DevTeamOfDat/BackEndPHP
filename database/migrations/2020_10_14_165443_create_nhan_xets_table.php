<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNhanXetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhan_xets', function (Blueprint $table) {
            $table->id('ma_nhan_xet');
            $table->bigInteger('ma_san_pham')->nullable();
            $table->bigInteger('ma_khach_hang');
            $table->text('binh_luan');
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
        Schema::dropIfExists('nhan_xets');
    }
}

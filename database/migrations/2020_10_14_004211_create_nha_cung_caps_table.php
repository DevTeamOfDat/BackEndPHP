<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNhaCungCapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nha_cung_caps', function (Blueprint $table) {
            $table->id('ma_nha_cung_cap');
            $table->string('ten');
            $table->string('dia_chi');
            $table->string('hot_line')->nullable();
            $table->string('email')->nullable();
            $table->string('so_dien_thoai');
            $table->string('hinh_anh')->nullable();
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
        Schema::dropIfExists('nha_cung_caps');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHinhAnhSanPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hinh_anh_san_phams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ma_san_pham');
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
        Schema::dropIfExists('hinh_anh_san_phams');
    }
}

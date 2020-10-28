<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLoaiDonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_dons', function (Blueprint $table) {
            $table->id();
            $table->string('gia_tri');
            $table->string('mo_ta')->default('');
            $table->boolean('isActive')->default(true);
        });

        DB::table('loai_dons')->insert([
            'gia_tri' => 'true',
            'mo_ta' => 'Lập trực tuyến',
        ]);
        DB::table('loai_dons')->insert([
            'gia_tri' => 'false',
            'mo_ta' => 'Lập tại cửa hàng',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loai_dons');
    }
}

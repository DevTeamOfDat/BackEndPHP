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

//                $faker = Faker\Factory::create();
//        $limit = 20;
//        $sps = \Illuminate\Support\Facades\DB::table('san_phams')->pluck('ma_san_pham');
////        $sps = array($sp);
//        $dts = \Illuminate\Support\Facades\DB::table('dac_trungs')->pluck('loai_dac_trung');
////        $dts = array($dt);
//
//        for ($i = 0; $i < $limit; $i++) {
//            DB::table('dac_trung_san_phams')->insert([
//                'loai_dac_trung' => $faker->randomElement($dts),
//                'ma_san_pham' => $faker->randomElement($sps),
//                'isActive' => $faker->randomElement([true, false]),
//            ]);
//        }
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

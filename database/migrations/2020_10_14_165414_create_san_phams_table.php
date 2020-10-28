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

//                $faker = Faker\Factory::create();
//        $limit = 20;
//        $ths = \Illuminate\Support\Facades\DB::table('thuong_hieus')->pluck('ma_thuong_hieu');
////        $ths = array($th);
//        $lsps = \Illuminate\Support\Facades\DB::table('loai_san_phams')->pluck('ma_loai_san_pham');
////        $lsps = array($lsp);
//
//        for ($i = 0; $i < $limit; $i++) {
//            DB::table('san_phams')->insert([
//                'ma_thuong_hieu' => $faker->randomElement($ths),
//                'ma_loai_san_pham' => $faker->randomElement($lsps),
//                'ten_san_pham' => $faker->name,
//                'gia_ban' => $faker->randomNumber(),
//                'so_luong' => $faker->randomNumber(),
////                'hinh_anh' => $faker->imageUrl(),
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
        Schema::dropIfExists('san_phams');
    }
}

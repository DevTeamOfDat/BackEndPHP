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

//        $faker = Faker\Factory::create();
//        $limit = 20;
//
//        $sps = \Illuminate\Support\Facades\DB::table('san_phams')->pluck('ma_san_pham');
//        $nkms = \Illuminate\Support\Facades\DB::table('ngay_khuyen_mais')->pluck('ma_ngay_khuyen_mai');
//
//        for ($i = 0; $i < $limit; $i++) {
//            DB::table('khuyen_mai_san_phams')->insert([
//                'ma_san_pham' => $faker->randomElement($sps),
//                'ma_ngay_khuyen_mai' => $faker->randomElement($nkms),
//                'muc_khuyen_mai' => rand(0, 100),
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
        Schema::dropIfExists('khuyen_mai_san_phams');
    }
}

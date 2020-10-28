<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiTietPhieuNhapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_phieu_nhaps', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->bigInteger('ma_phieu_nhap');
            $table->bigInteger('ma_san_pham');
            $table->double('gia_nhap', 15, 2)->default(0.00);
            $table->integer('so_luong')->default(0);
            $table->boolean('isActive')->default(true);
        });

//        $faker = Faker\Factory::create();
//        $limit = 20;
//        $sps = \Illuminate\Support\Facades\DB::table('san_phams')->pluck('ma_san_pham');
//        $pns = \Illuminate\Support\Facades\DB::table('phieu_nhaps')->pluck('ma_phieu_nhap');
//
//        for ($i = 0; $i < $limit; $i++) {
//            DB::table('chi_tiet_phieu_nhaps')->insert([
//                'ma_phieu_nhap' => $faker->randomElement($pns),
//                'ma_san_pham' => $faker->randomElement($sps),
//                'gia_nhap' => $faker->randomNumber(),
//                'so_luong' => $faker->randomNumber(),
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
        Schema::dropIfExists('chi_tiet_phieu_nhaps');
    }
}

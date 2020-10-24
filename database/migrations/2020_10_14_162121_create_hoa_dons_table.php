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
            $table->integer('ma_nhan_vien');
            $table->integer('ma_khach_hang');
            $table->string('ngay_lap');
            $table->boolean('loai_don');
            $table->boolean('trang_thai');
            $table->double('tong_tien', 15, 2)->default(0.00);
            $table->boolean('isActive')->default(true);
        });

        $faker = Faker\Factory::create();
        $limit = 20;

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $khs = \Illuminate\Support\Facades\DB::table('tai_khoans')->where('loai_tai_khoan', '=', 'KH')->pluck('ma_tai_khoan');
//        $khs = array($kh);
        $nvs = \Illuminate\Support\Facades\DB::table('tai_khoans')->where('loai_tai_khoan', '=', 'NV')->pluck('ma_tai_khoan');
//        $nvs = array($nv);

        for ($i = 0; $i < $limit; $i++) {
            DB::table('hoa_dons')->insert([
                'ma_nhan_vien' => $faker->randomElement($nvs),
                'ma_khach_hang' => $faker->randomElement($khs),
                'ngay_lap' => $faker->date('d-m-Y | H:i', time()),
                'loai_don' => $faker->randomElement([true, false]),
                'trang_thai' => $faker->randomElement([true, false]),
                'isActive' => $faker->randomElement([true, false]),
            ]);
        }
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
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
            $table->integer('ma_nhan_vien');
            $table->integer('ma_nha_cung_cap');
            $table->date('ngay_nhap')->default(now());
            $table->double('tong_tien', 15, 2)->default(0.00);
            $table->string('ghi_chu')->default('');
            $table->boolean('isActive')->default(true);
        });

        $faker = Faker\Factory::create();
        $limit = 20;

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $nccs = \Illuminate\Support\Facades\DB::table('nha_cung_caps')->pluck('ma_nha_cung_cap');
        $nvs = \Illuminate\Support\Facades\DB::table('tai_khoans')->where('loai_tai_khoan', '=', 'QT')->pluck('ma_tai_khoan');

        for ($i = 0; $i < $limit; $i++) {
            DB::table('phieu_nhaps')->insert([
                'ma_nhan_vien' => $faker->randomElement($nvs),
                'ma_nha_cung_cap' => $faker->randomElement($nccs),
                'ngay_nhap' => $faker->date('d-m-Y'),
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
        Schema::dropIfExists('phieu_nhaps');
    }
}

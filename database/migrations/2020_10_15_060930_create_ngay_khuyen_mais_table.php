<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNgayKhuyenMaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngay_khuyen_mais', function (Blueprint $table) {
            $table->id('ma_ngay_khuyen_mai');
            $table->date('ngay_gio');
            $table->boolean('isActive')->default(true);
        });

        $faker = Faker\Factory::create();
        $limit = 20;

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        for ($i = 0; $i < $limit; $i++) {
            DB::table('ngay_khuyen_mais')->insert([
                'ngay_gio' => $faker->date('d-m-Y', time()),
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
        Schema::dropIfExists('ngay_khuyen_mais');
    }
}

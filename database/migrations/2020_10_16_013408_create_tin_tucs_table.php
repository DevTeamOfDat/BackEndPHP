<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTinTucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tin_tucs', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de');
            $table->text('noi_dung');
            $table->string('highlight')->default('');
            $table->string('thumbnail')->default('');
            $table->string('url')->default('');
            $table->string('ngay_dang')->default(date('Y-m-d', time()));
            $table->boolean('isActive')->default(true);
        });
        $faker = Faker\Factory::create();
        $limit = 20;
        for ($i = 0; $i < $limit; $i++) {
            DB::table('tin_tucs')->insert([
                'tieu_de' => $faker->title,
                'noi_dung' => $faker->text,
                'highlight' => strtoupper($faker->title),
                'thumbnail' => $faker->url,
                'url' => $faker->imageUrl(),
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
        Schema::dropIfExists('tin_tucs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDacTrungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dac_trungs', function (Blueprint $table) {
            $table->id('loai_dac_trung');
            $table->string('ten_dac_trung');
            $table->string('mo_ta')->default('');
            $table->boolean('isActive')->default(true);
        });

                $faker = Faker\Factory::create();
        $limit = 20;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('dac_trungs')->insert([
                'ten_dac_trung' => $faker->name,
                'mo_ta' => $faker->text,
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
        Schema::dropIfExists('dac_trungs');
    }
}

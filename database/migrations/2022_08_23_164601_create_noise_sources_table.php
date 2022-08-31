<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noise_sources', function (Blueprint $table) {
            $table->id();
            $table->boolean('check_source')->default(false);
            $table->string('name');
            $table->string('mark')->nullable();
            $table->float('distance')->nullable();
            $table->float('la_31_5')->nullable();
            $table->float('la_63')->nullable();
            $table->float('la_125')->nullable();
            $table->float('la_250')->nullable();
            $table->float('la_500')->nullable();
            $table->float('la_1000')->nullable();
            $table->float('la_2000')->nullable();
            $table->float('la_4000')->nullable();
            $table->float('la_8000')->nullable();
            $table->float('la_eq')->nullable();
            $table->float('la_max')->nullable();
            $table->text('foundation');
            $table->text('remark')->nullable();
            $table->unsignedBigInteger('id_file_path');
            $table->unsignedBigInteger('id_type_of_source');
            $table->unsignedBigInteger('id_user');

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_file_path')->references('id')->on('file_noise_sources');
            $table->foreign('id_type_of_source')->references('id')->on('type_noise_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('noise_sources');
    }
};

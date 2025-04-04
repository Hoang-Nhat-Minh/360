<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotlinksSpecialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotlinks_special', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->string('location_id');
            $table->string('yaw');
            $table->string('pitch');
            $table->string('video_link')->nullable();
            $table->json('info_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotlinks_special');
    }
}

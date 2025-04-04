<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotlinksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('hotlinks', function (Blueprint $table) {
      $table->id();
      $table->integer('type');
      $table->string('location_id');
      $table->string('link_to_location_id');
      $table->string('yaw');
      $table->string('pitch');
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
    Schema::dropIfExists('hotlinks');
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('locations', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug');
      $table->string('paronama_id');
      $table->string('next_location_id')->nullable();
      $table->string('previous_location_id')->nullable();
      $table->boolean('entry');
      $table->boolean('status');
      $table->string('sun')->nullable();
      $table->string('voice')->nullable();
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
    Schema::dropIfExists('locations');
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->json('description')->nullable();
            $table->string('voice_en')->nullable();
            $table->json('name')->change();
        });
    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['description', 'voice_en']);
            $table->string('name')->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataFieldToLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->json('eyes')->nullable()->after('voice');
            $table->integer('sort')->nullable()->after('voice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('eyes');
            $table->dropColumn('sort');
        });
    }
}

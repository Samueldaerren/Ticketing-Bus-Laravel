<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->integer('capacity')->after('bus_name');
            $table->string('bus_type')->after('capacity');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'bus_type']);
        });
    }
};

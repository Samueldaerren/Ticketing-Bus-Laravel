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
        $table->string('bus_name')->after('bus_number');
        $table->date('arrival_date')->nullable()->after('departure_date');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('tickets', function (Blueprint $table) {
        $table->dropColumn(['bus_name', 'arrival_date']);
    });
}
};

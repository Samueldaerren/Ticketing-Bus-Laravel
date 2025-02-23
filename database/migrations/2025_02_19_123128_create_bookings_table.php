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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke users
        $table->foreignId('ticket_id')->constrained()->onDelete('cascade'); // Relasi ke tickets
        $table->integer('jumlah_kursi');
        $table->decimal('total_harga', 15, 2);
        $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

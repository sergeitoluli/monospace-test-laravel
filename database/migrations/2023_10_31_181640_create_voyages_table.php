<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vessel_id');
            $table->string('code');
            $table->date('start');
            $table->date('end');
            $table->enum('status', ['pending', 'ongoing', 'submitted'])->default('pending');
            $table->decimal('revenues', 8, 2);
            $table->decimal('expenses', 8, 2);
            $table->decimal('profit', 8, 2);
            $table->foreign('vessel_id')->references('id')->on('vessels')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voyages');
    }
};

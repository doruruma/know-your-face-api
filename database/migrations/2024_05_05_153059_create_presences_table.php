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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->time('schedule_time_in');
            $table->time('schedule_time_out');
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->string('longitude_clock_in');
            $table->string('latitude_clock_in');
            $table->string('longitude_clock_out')->nullable();
            $table->string('latitude_clock_out')->nullable();
            $table->string('face_image_clock_in');
            $table->string('face_image_clock_out')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};

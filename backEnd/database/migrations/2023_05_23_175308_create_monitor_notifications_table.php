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
        Schema::create('monitor_notifications', function (Blueprint $table) {
            $table->id('montification_id');
            $table->foreignId('monitor_id')->references('monitor_id')->on('monitors');
            $table->foreignId('notification_id')->references('notification_id')->on('notifications');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor_notifications');
    }
};

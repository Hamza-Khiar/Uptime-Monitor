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
        Schema::create('checks', function (Blueprint $table) {
            $table->uuid('check_uuid')->primary();
            $table->foreignId('monitor_id')->references('id')->on('monitors');
            $table->integer('status_code');
            $table->timestamp('timestamp');
            $table->string('response_time');
            $table->boolean('ssl_certificate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checks');
    }
};

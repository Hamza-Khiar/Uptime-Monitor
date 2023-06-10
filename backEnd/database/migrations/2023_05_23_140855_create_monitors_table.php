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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('friendly_name');
            $table->string('url');
            $table->boolean('active')->default(true);
            $table->boolean('is_paused')->default(false);
            $table->bigInteger('total_uptime');
            $table->json('ssl_certificate');
            $table->integer('interval');
            $table->json('tags');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};

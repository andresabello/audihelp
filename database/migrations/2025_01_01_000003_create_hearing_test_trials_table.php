<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hearing_test_trials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_id')->index();
            $table->string('ear');           // left | right
            $table->integer('frequency_hz'); // 20–20000 Hz
            $table->decimal('level_dbfs', 6, 2); // -96.00–0.00
            $table->string('pattern_type')->default('tone'); // tone | pulse | am
            $table->string('response');      // hear_clearly | barely_hear | feel | nothing
            $table->timestamp('created_at');

            $table->foreign('session_id')->references('id')->on('hearing_test_sessions')->cascadeOnDelete();
            $table->index(['session_id', 'ear', 'frequency_hz']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hearing_test_trials');
    }
};

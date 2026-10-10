<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hearing_thresholds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('profile_id')->index();
            $table->string('ear');           // left | right
            $table->integer('frequency_hz');
            $table->decimal('level_dbfs', 6, 2);
            $table->string('response');      // hear_clearly | barely_hear | feel | nothing
            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('hearing_profiles')->cascadeOnDelete();
            $table->index(['profile_id', 'ear', 'frequency_hz']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hearing_thresholds');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alfda_decisions', function (Blueprint $table) {
            $table->id();
            // No FK constraint until existing sensor_data primary-key type is verified.
            $table->unsignedBigInteger('sensor_data_id')->unique();
            $table->unsignedBigInteger('tanaman_id')->nullable()->index();
            $table->string('decision', 40);
            $table->string('message', 255);
            $table->decimal('score', 8, 4)->default(0);
            $table->decimal('delta_tds', 12, 3)->default(0);
            $table->decimal('delta_level', 12, 3)->default(0);
            $table->decimal('adaptive_tds', 12, 3)->nullable();
            $table->decimal('adaptive_level', 12, 3)->nullable();
            $table->decimal('inference_time_ms', 12, 4)->nullable();
            $table->string('mode', 20)->default('shadow');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('alfda_decisions');
    }
};

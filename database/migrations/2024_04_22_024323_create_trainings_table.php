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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('training_code');
            $table->string('training_name');
            $table->string('description');
            $table->timestamp('training_start');
            $table->timestamp('training_end');
            $table->integer('training_hours')->default(8);
            $table->json('training_type');
            $table->json('training_venue');
            $table->json('training_speakers');
            $table->timestamp('issuance_date');
            $table->integer('director_id');
            $table->integer('hr_head_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};

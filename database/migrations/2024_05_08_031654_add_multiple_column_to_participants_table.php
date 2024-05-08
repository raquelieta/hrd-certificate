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
        Schema::table('participants', function (Blueprint $table) {
            $table->string('or_number')->after('training_id')->nullable();
            $table->string('suffix')->after('middle_initial')->nullable();
            $table->string('civil_status')->after('position');
            $table->string('rank')->after('employment_status');
            $table->float('years_in_service')->after('rank');
            $table->string('province')->after('years_in_service');
            $table->string('government_sector')->after('sex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['or_number','suffix','civil_status','rank','years_in_service','province','government_sector']);
        });
    }
};

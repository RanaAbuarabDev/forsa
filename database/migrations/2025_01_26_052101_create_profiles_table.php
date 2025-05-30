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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
            $table->string('img')->nullable();
            $table->date('BD')->nullable();
            $table->string('residence_area')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('employment_status', ['employed','unemployed','seeking_better_opportunity'])->nullable();
            $table->string('cv_path')->nullable();
            $table->timestamps();
            
        });
        Schema::table('profiles', function (Blueprint $table) {
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

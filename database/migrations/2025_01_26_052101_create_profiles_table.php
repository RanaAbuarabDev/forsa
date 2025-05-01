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
            $table->string('PhonNum')->nullable();
            $table->string('bio')->nullable();
            $table->date('BD')->nullable();
            $table->timestamps();
            
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('Age');
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

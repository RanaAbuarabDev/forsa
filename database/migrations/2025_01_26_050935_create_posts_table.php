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
        Schema::create('posts', function (Blueprint $table) {
        $table->id();
        
        
        $table->string('type'); 
        $table->text('description');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('governorate_id')->constrained()->onDelete('cascade');

        $table->enum('work_mode', ['full_time', 'part_time','permanent','temporary'])->nullable();
        $table->enum('job_type', ['online', 'on_site','onlin_or_onSite'])->nullable();
        $table->boolean('is_bookable')->default(false);
        $table->string('salary')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

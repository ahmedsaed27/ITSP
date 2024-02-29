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
        Schema::create('interview_date', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('review_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            
            $table->unsignedBigInteger('review_id');
            $table->foreign('review_id')->references('id')->on('review')->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('task')->nullable();
            $table->dateTime('date');
            $table->string('mail_to');
            $table->boolean('attend')->default(0)->comment('0 => absence , 1 => attend');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_date');
    }
};

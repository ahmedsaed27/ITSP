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
        Schema::create('final_interview_status', function (Blueprint $table) {
            $table->id();
         

            $table->unsignedBigInteger('interview_date_id');
            $table->foreign('interview_date_id')->references('id')->on('interview_date')->cascadeOnDelete()->cascadeOnUpdate();

            $table->dateTime('date');
            $table->tinyInteger('status')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_interview_status');
    }
};

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
        Schema::create('applicant', function (Blueprint $table) {
            $table->id();
            $table->string('cv');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('phone')->index();
            $table->foreignId('citys_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('area');
            $table->string('birthYear');
            $table->boolean('gender');
            $table->string('images');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant');
    }
};

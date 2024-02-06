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
        Schema::create('reals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_members_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title' , 100);
            $table->string('vedioLink');
            $table->longText('description');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reals');
    }
};

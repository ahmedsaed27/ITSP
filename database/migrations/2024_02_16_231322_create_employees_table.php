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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // $table->string('name');
            // $table->string('password');
            // $table->string('email');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('phone');
            $table->longText('address');
            $table->boolean('gander')->comment('0 => female , 1 => male');
            $table->string('college');
            $table->string('university');
            $table->text('Specialization');
            $table->string('position_type');
            $table->longText('skils');
            $table->foreignId('departments_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

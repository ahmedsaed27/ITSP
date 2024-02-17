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
        Schema::create('leave_request', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            // $table->date('to');
            $table->foreignId('employees_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('note');
            $table->tinyInteger('status')->comment('0 => waiting , 1 => Acceptable , 2 => Rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_request');
    }
};

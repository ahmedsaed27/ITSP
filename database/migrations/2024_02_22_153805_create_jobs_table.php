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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categories_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('departments_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('image');
            $table->string('postion');
            $table->longText('discription');
            $table->tinyInteger('job_level'); // jonuir - senior
            $table->tinyInteger('job_type'); //  part time - full time
            $table->tinyInteger('job_place'); // remotly - on site
            $table->string('range_salary');
            $table->longText('skills');
            $table->longText('requirments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};

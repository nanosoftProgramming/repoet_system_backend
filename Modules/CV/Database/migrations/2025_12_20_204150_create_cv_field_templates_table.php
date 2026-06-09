<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_field_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['Student', 'Instructor', 'Trainer']);
            $table->string('label');
            $table->string('field_key');
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->text('placeholder')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['role', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_field_templates');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->enum('role', ['Super Admin', 'Trainer', 'Student']);
            $table->string('identity_number')->nullable()->unique();
            $table->string('username')->nullable()->unique();
            $table->string('national_number')->nullable()->unique();
            $table->date('birth_date')->nullable();
            $table->string('file')->nullable();
            $table->rememberToken();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

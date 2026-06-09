<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();

            // اسم الاستبيان
            $table->string('name');

            // الأكاديمية المالكة للاستبيان
            $table->unsignedBigInteger('academy_id')->nullable();

            // من أنشأ الاستبيان
            $table->unsignedBigInteger('created_by');

            // فعال أو لا
            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->foreign('academy_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
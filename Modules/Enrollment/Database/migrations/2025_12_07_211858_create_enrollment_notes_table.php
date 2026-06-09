<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Enrollment\App\Models\Enrollment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollment_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Enrollment::class)->index()->constrained()->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->string('file')->nullable();
            $table->enum('status', ['reviewed', 'in_progress', 'cancelled'])->default('in_progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_notes');
    }
};

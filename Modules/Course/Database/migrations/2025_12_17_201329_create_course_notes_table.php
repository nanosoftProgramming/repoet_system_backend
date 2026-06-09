<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Course\App\Models\Course;
use Modules\User\App\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'trainer_id')->nullable()->index()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('course_notes');
    }
};

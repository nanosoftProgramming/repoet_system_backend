<?php

use Modules\User\App\Models\User;
use Modules\Course\App\Models\Course;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'student_id')->index()->constrained('users')->restrictOnDelete();
            $table->foreignIdFor(Course::class)->index()->constrained()->restrictOnDelete();
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_paid')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};

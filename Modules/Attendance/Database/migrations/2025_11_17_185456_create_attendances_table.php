<?php

use Modules\User\App\Models\User;
use Modules\Course\App\Models\Course;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Enrollment\App\Models\Enrollment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Enrollment::class)->index()->constrained()->restrictOnDelete();
            $table->boolean('is_present')->default(0);
            $table->timestamps();
            $table->foreignIdFor(User::class, 'student_id')->index()->constrained('users')->restrictOnDelete();
            $table->foreignIdFor(Course::class)->index()->constrained()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

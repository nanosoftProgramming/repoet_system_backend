<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\User\App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'student_id')->index()->constrained('users')->cascadeOnDelete();
            $table->string('answerable_type')->nullable();
            $table->unsignedBigInteger('answerable_id')->nullable();
            $table->timestamps();

            $table->index(['answerable_type', 'answerable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_submissions');
    }
};

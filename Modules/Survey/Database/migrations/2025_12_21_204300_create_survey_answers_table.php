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
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_submission_id')->constrained('survey_submissions')->cascadeOnDelete();
            $table->foreignId('survey_question_id')->index()->constrained()->cascadeOnDelete();
            $table->text('answer')->nullable();
            $table->timestamps();

            $table->index(['survey_submission_id', 'survey_question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};

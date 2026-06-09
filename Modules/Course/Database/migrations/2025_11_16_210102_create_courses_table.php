<?php


use Modules\User\App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignIdFor(User::class, 'trainer_id')->nullable()->index()->constrained('users')->nullOnDelete();
            $table->unsignedSmallInteger('sessions_no')->default(0);
            $table->unsignedDecimal('price', 10, 2)->default(0);
            $table->date('date')->nullable();
            $table->string('survey_file')->nullable();
            $table->string('details_file')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

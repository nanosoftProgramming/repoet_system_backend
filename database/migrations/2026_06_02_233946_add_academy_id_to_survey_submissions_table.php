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
    Schema::table('survey_submissions', function (Blueprint $table) {
        // إضافة عمود academy_id لربط الاستبيان بالأكاديمية
        $table->foreignId('academy_id')->nullable()->constrained('users')->cascadeOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('survey_submissions', function (Blueprint $table) {
        $table->dropForeign(['academy_id']);
        $table->dropColumn('academy_id');
    });
}
};

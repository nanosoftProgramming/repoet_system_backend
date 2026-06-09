<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('survey_submissions', function (Blueprint $table) {
        $table->string('student_name')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('survey_submissions', function (Blueprint $table) {
        $table->dropColumn('student_name');
    });
    
}
};

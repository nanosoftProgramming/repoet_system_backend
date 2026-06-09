<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Super Admin', 'Trainer', 'Student', 'Instructor'])->change();
        });
        Role::create(['name' => 'Instructor', 'guard_name' => 'user']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};

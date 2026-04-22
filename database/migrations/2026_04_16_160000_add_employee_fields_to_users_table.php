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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default('company_admin')->after('password');
            $table->string('team')->nullable()->after('user_type');
            $table->string('department')->nullable()->after('team');
            $table->string('employee_role')->nullable()->after('department');
            $table->json('privacy_settings')->nullable()->after('employee_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'team',
                'department',
                'employee_role',
                'privacy_settings',
            ]);
        });
    }
};

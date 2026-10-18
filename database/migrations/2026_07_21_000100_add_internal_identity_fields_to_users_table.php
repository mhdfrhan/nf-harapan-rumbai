<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->ulid('public_id')->nullable()->unique()->after('id');
            $table->string('username', 50)->nullable()->unique()->after('name');
            $table->string('role', 32)->default('cashier')->index()->after('email');
            $table->boolean('is_active')->default(true)->index()->after('role');
            $table->boolean('force_password_change')->default(false)->after('is_active');
            $table->timestamp('last_login_at')->nullable()->after('force_password_change');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['public_id']);
            $table->dropUnique(['username']);
            $table->dropColumn(['public_id', 'username', 'role', 'is_active', 'force_password_change', 'last_login_at']);
        });
    }
};

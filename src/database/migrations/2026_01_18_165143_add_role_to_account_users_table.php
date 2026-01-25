<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('account_users', function (Blueprint $table) {
            // Add role if missing
            $table->string('role', 50)->default('owner')->after('account_id');

            // Strongly recommended for production ops
            if (!Schema::hasColumn('account_users', 'created_at')) {
                $table->timestamps();
            }

            // Prevent duplicate membership rows
            $table->unique(['account_id', 'platform_user_id'], 'account_users_account_platform_unique');
        });

        // Optional: backfill role for existing rows (keep it safe)
        // If you already have some rows, they will get default('owner') automatically.
        // But if you want different default, change above.
    }

    public function down(): void
    {
        Schema::table('account_users', function (Blueprint $table) {
            $table->dropUnique('account_users_account_platform_unique');

            if (Schema::hasColumn('account_users', 'created_at')) {
                $table->dropTimestamps();
            }

            $table->dropColumn('role');
        });
    }
};

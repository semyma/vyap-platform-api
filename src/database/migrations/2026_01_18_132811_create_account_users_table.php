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
        Schema::create('account_users', function (Blueprint $table) {
             $table->id();

    $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
    $table->foreignId('platform_user_id')->constrained('platform_users')->cascadeOnDelete();

    $table->string('account_role')->default('member'); // owner/admin/member (account-level)
    $table->boolean('active')->default(true);

    $table->timestamps();

    $table->unique(['account_id', 'platform_user_id']);
    $table->index(['platform_user_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_users');
    }
};

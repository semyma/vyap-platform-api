<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_users', function (Blueprint $table): void {
            $table->id();

            $table->string('name', 120)->default('');
            $table->string('dial_code', 8);
            $table->string('phone', 20);

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->unique(['dial_code', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_users');
    }
};

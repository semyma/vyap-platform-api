<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_otp_challenges', function (Blueprint $table): void {
            $table->id();

            $table->string('token', 128)->unique();     // server-issued token
            $table->string('dial_code', 8);
            $table->string('phone', 20);               // national number digits only
            $table->string('purpose', 20);             // login|register
            $table->string('otp_hash', 255);

            $table->unsignedBigInteger('expires_at');   // epoch seconds
            $table->boolean('used')->default(false);

            $table->timestamps();

            $table->index(['dial_code', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_otp_challenges');
    }
};

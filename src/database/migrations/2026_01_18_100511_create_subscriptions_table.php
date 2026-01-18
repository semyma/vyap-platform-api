<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('platform_user_id')
                ->constrained('platform_users')
                ->cascadeOnDelete();

            $table->string('service_code'); // billing, rental, delivery, etc.
            $table->string('status'); // active, expired, cancelled

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->string('source')->default('manual'); // manual, payment, trial

            $table->timestamps();

            $table->unique(['platform_user_id', 'service_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

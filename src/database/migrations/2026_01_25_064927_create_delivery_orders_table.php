<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('account_id')->index();
            $table->unsignedBigInteger('source_bill_id')->nullable()->index();

            $table->string('customer_name', 190);
            $table->string('customer_phone', 30);
            $table->string('address_line', 500);
            $table->string('pincode', 20)->nullable();

            $table->string('status', 30)->index(); // pending/assigned/out_for_delivery/delivered/failed
            $table->unsignedBigInteger('assigned_account_user_id')->nullable()->index();

            $table->decimal('amount_to_collect', 12, 2)->default(0);
            $table->decimal('collected_amount', 12, 2)->default(0);
            $table->string('collected_via', 20)->nullable(); // cash/upi/bank/card
            $table->string('collection_ref', 100)->nullable();
            $table->timestamp('collected_at')->nullable();

            $table->string('failure_reason', 500)->nullable();

            $table->unsignedBigInteger('created_by_account_user_id')->index();

            $table->timestamps();

            // FKs optional depending on your current DB constraint style.
            // If Platform tables exist:
            // $table->foreign('account_id')->references('id')->on('accounts');
            // $table->foreign('assigned_account_user_id')->references('id')->on('account_users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};

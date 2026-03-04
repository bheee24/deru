<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // ── Customer ──
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // ── Reference ──
            $table->string('reference')->unique();            // DERU-XXXXXXXX
            $table->string('payment_intent_id')->unique();    // Stripe pi_xxx

            // ── Card info (last 4 only — never store full card) ──
            $table->string('card_last4', 4)->nullable();
            $table->string('card_brand')->nullable();         // visa, mastercard, amex

            // ── Amounts ──
            $table->decimal('subtotal',       10, 2);
            $table->decimal('shipping_cost',  10, 2)->default(0);
            $table->decimal('total',          10, 2);

            // ── Shipping ──
            $table->string('shipping_method');                // DPD Delivery (1–2 Days) etc.
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('postcode');
            $table->string('country', 2);

            // ── Status ──
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
                  ->default('pending');

            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ── Order items (one row per product line) ──
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');                   // snapshot at time of purchase
            $table->decimal('price',    10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);               // price × quantity
            $table->string('product_img')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
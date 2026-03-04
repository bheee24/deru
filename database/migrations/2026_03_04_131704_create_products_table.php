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
        Schema::create('products', function (Blueprint $table) {
           $table->id();
           $table->uuid("product_unique_id");
           $table->string('name');
           $table->decimal('price', 10, 2);
           $table->longText("description")->nullable();
           $table->boolean("is_sold_out")->default(false);
           $table->text('summary')->nullable();
           $table->string('image')->nullable();
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
            $table->boolean('in_stock')->default(true)->after('price');
        });


        // Back-fill slugs for existing products
        // Product::all()->each(function ($product) {
        //     $product->update([
        //         'slug' => Str::slug($product->name) . '-' . $product->id,
        //     ]);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['slug', 'in_stock']);
        });
    }
};

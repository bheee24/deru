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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');

            // Default saved address (pre-fills checkout)
            $table->string('address_line1')->nullable()->after('last_name');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('city')->nullable()->after('address_line2');
            $table->string('postcode')->nullable()->after('city');
            $table->string('country', 2)->nullable()->default('GB')->after('postcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->dropColumn([
                'first_name', 'last_name',
                'address_line1', 'address_line2',
                'city', 'postcode', 'country',
            ]);


        });
    }
};

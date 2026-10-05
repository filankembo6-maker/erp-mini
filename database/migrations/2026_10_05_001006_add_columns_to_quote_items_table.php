<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->decimal('discount_percent', 5, 2)->default(0)->after('unit_price');
            $table->decimal('tax_percent', 5, 2)->default(0)->after('discount_percent');
            $table->decimal('line_subtotal', 12, 2)->default(0)->after('tax_percent');
        });
    }

    public function down(): void
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropColumn(['discount_percent', 'tax_percent', 'line_subtotal']);
        });
    }
};
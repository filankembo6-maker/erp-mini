<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->decimal('purchase_price', 12, 2)->default(0)->after('price');
            $table->string('barcode')->nullable()->after('sku');
            $table->string('photo')->nullable()->after('description');
            $table->boolean('is_active')->default(true)->after('stock_alert');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'purchase_price', 'barcode', 'photo', 'is_active']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->default(0)->after('total_amount');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('tax_amount');
            $table->date('valid_until')->nullable()->after('status');
            $table->text('notes')->nullable()->after('valid_until');
            $table->text('payment_terms')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax_amount', 'discount_amount', 'valid_until', 'notes', 'payment_terms']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('status');
            $table->decimal('paid_amount', 12, 2)->default(0)->after('total_amount');
            $table->decimal('subtotal', 12, 2)->default(0)->after('total_amount');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('tax_amount');
            $table->text('notes')->nullable()->after('due_date');
            $table->boolean('is_locked')->default(false)->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'paid_amount', 'subtotal', 'tax_amount', 'discount_amount', 'notes', 'is_locked']);
        });
    }
};
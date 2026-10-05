<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('type', ['particulier', 'entreprise'])->default('entreprise')->after('name');
            $table->decimal('credit_limit', 12, 2)->default(0)->after('city');
            $table->text('notes')->nullable()->after('credit_limit');
            $table->enum('segment', ['nouveau', 'regulier', 'vip'])->default('nouveau')->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['type', 'credit_limit', 'notes', 'segment']);
        });
    }
};
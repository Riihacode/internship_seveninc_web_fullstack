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
        Schema::table('stock_transactions', function (Blueprint $table) {
            // Kolom tambahan untuk audit trail
            if (!Schema::hasColumn('stock_transactions', 'reference')) {
                $table->string('reference', 64)
                      ->nullable()
                      ->after('id');
            }

            if (!Schema::hasColumn('stock_transactions', 'approved_by')) {
                $table->foreignId('approved_by')
                      ->nullable()
                      ->after('user_id')
                      ->constrained('users')
                      ->nullOnDelete(); // onDelete set null
            }

            if (!Schema::hasColumn('stock_transactions', 'unit_cost')) {
                $table->decimal('unit_cost', 15, 2)
                      ->nullable()
                      ->after('quantity');
            }

            // Tambahkan timestamps jika belum ada
            if (!Schema::hasColumns('stock_transactions', ['created_at', 'updated_at'])) {
                $table->timestamps();
            }

            // Tambahkan index tambahan (hanya yang penting)
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transactions', 'reference')) {
                $table->dropColumn('reference');
            }
            if (Schema::hasColumn('stock_transactions', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('stock_transactions', 'unit_cost')) {
                $table->dropColumn('unit_cost');
            }

            // Jangan hapus timestamps (umumnya dipakai di semua tabel)
        });
    }
};

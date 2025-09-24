<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // /**
    //  * Run the migrations.
    //  */
    // public function up(): void
    // {
    //     Schema::table('stock_transactions', function (Blueprint $table) {
    //         //
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::table('stock_transactions', function (Blueprint $table) {
    //         //
    //     });
    // }

    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->foreignId('correction_of')
                  ->nullable()
                  ->after('id')
                  ->constrained('stock_transactions')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['correction_of']);
            $table->dropColumn('correction_of');
        });
    }
};

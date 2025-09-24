<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('stock_transactions', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignId('product_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
    //         $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
    //         $table->string('type'); // "Masuk" | "Keluar"
    //         $table->integer('quantity');
    //         $table->date('date');
    //         $table->string('status');   // "Pending" | "Diterima | "Ditolak" | "Dikeluarkan"
    //         $table->text('notes')->nullable();
    //         $table->timestamps();
    //         $table->softDeletes();
    //     });
    // }
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();

            // Relasi utama
            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('supplier_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Data transaksi
            $table->string('type');       // "Masuk" | "Keluar"
            $table->integer('quantity');
            $table->date('date');
            $table->string('status');     // "Pending" | "Diterima" | "Ditolak" | "Dikeluarkan"
            $table->text('notes')->nullable();

            // Tracking waktu + soft delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};

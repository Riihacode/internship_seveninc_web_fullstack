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

            $table->foreignId('user_id') // user yang membuat transaksi (staff/manager)
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // 🔹 Manager yang memberi tugas (assigned_by) & Staff yang menerima tugas (assigned_to)
            $table->foreignId('assigned_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // Data transaksi
            $table->string('type'); // "Masuk" | "Keluar"
            $table->integer('quantity');
            $table->decimal('unit_cost', 15, 2)->nullable(); // audit trail biaya
            $table->date('date');

            // 🔹 Status workflow lebih detail
            $table->enum('status', [
                'Pending',      // baru dibuat staff/manager
                'In Progress',  // sedang dikerjakan staff
                'Completed',    // selesai dikerjakan staff
                'Diterima',     // sudah disetujui manager/admin
                'Ditolak',      // ditolak manager/admin
                'Dikeluarkan'   // barang keluar (dispatch)
            ])->default('Pending');

            $table->text('notes')->nullable();

            // Audit trail
            $table->string('reference', 64)->nullable();       // kode referensi transaksi
            $table->foreignId('approved_by')                   // manager/admin yang approve
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Self reference untuk koreksi transaksi
            $table->foreignId('correction_of')
                  ->nullable()
                  ->constrained('stock_transactions')
                  ->nullOnDelete();

            // Tracking waktu + soft delete
            $table->timestamps();
            $table->softDeletes();

            // Index tambahan
            $table->index('date');
            $table->index('status');
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

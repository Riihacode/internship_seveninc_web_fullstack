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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // ❌ tidak bisa hapus kategori jika masih ada produk

            $table->foreignId('supplier_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // ❌ tidak bisa hapus supplier jika masih ada produk

            // Data produk
            $table->string('name', 255);
            $table->string('sku', 100)->unique();
            $table->text('description')->nullable();

            // Harga & Stok
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->unsignedInteger('minimum_stock')->default(0);
            $table->unsignedInteger('current_stock')->default(0);

            // Path gambar
            $table->string('image')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

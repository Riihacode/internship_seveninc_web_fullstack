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
    //     Schema::create('products', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    // }
    // public function up(): void
    // {
    //     Schema::create('products', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
    //         $table->foreignId('supplier_id')->constrained()->cascadeOnUPdate()->restrictOnDelete();
    //         $table->string('name');
    //         $table->string('sku')->unique();
    //         $table->text('description')->nullable();    // Stock keeping unit
    //         $table->decimal('purchase_price', 15, 2);
    //         $table->decimal('selling_price', 15, 2);
    //         $table->string('image')->nullable();    // path to file image
    //         $table->unsignedInteger('minimum_stock');
    //         $table->timestamps();
    //     });
    // }
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();

            // Data produk
            $table->string('name', 255);
            $table->string('sku', 100)->unique();
            $table->text('description')->nullable();

            // Harga & Stok
            $table->decimal('purchase_price', 15, 2); // skala industri: cukup besar
            $table->decimal('selling_price', 15, 2);
            $table->unsignedInteger('minimum_stock')->default(0);
            $table->unsignedInteger('current_stock')->default(0);

            // Path gambar
            $table->string('image')->nullable();

            $table->timestamps();
            $table->softDeletes(); // standar industri: untuk audit trail
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

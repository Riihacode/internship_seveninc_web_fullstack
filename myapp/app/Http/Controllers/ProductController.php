<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(\App\Models\Product::class, 'product');
    }

    // =======================================================
    // = ... index, create, store, edit, update, destroy ... =
    // =======================================================
    public function index(Request $request)
    {
        $products = Product::with(['category', 'supplier'])
            ->when($request->q, fn($q,$v) =>
                $q->where('name','like',"%$v%")
                  ->orWhere('sku','like',"%$v%")
            )
            ->paginate(10);

        return view('products.index', compact('products'));
    }
    
    public function create()
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
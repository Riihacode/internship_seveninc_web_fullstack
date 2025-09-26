<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;

class ProductAttributeController extends Controller
{
    public function index() 
    {
        $attributes = ProductAttribute::with('product')->paginate(10);
        return view('attributes.index', compact('attributes'));
    }

    public function create()  
    {
        $products = Product::all();
        return view('attributes.create', compact('products'));
    }

    public function store(Request $request) 
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
            'value'      => 'nullable|string|max:255',
        ]);

        ProductAttribute::create($data);

        return redirect()->route('attributes.index')
                        ->with('success', 'Atribut produk berhasil ditambahkan.');
    }

    public function destroy(ProductAttribute $attribute)
    {
        $attribute->delete();

        return back()->with('success', 'Atribut berhasil dihapus.');
    }
    
    public function edit(ProductAttribute $attribute)
    {
        $products = Product::all();
        return view('attributes.edit', compact('attribute', 'products'));
    }

    public function update(Request $request, ProductAttribute $attribute)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
            'value'      => 'nullable|string|max:255',
        ]);

        $attribute->update($data);

        return redirect()->route('attributes.index')
                        ->with('success', 'Atribut produk berhasil diperbarui.');
    }
}

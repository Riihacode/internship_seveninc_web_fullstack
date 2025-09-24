<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;

class ProductAttributeController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }

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
            'product_id'    => 'required|exists:products,id',
            'name'          => 'required|string|max:255',
            'value'         => 'nullable|string|max:255',
        ]);

        // ProductAttribute::create($request->all());
        ProductAttribute::create($data);

        return redirect()->route('attributes.index')
                         ->with('success', 'Atribut produk berhasil ditambahkan.');
    }

    public function destroy(ProductAttribute $attributes)
    {
        $attributes->delete();

        return back()->with('success', 'Atribut berhasil dihapus');
    }
    
    public function edit(ProductAttribute $attribute)
    {
        $products = Product::all();
        return view('attributes.edit', compact('attribute', 'products'));
    }

    public function update(Request $request, ProductAttribute $attribute)
    {
        $data = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'name'          => 'required|string|max:255',
            'value'         => 'nullable|string|max:255',
        ]);

        $attribute->update($data);

        return redirect()->route('attributes.index')
                        ->with('success', 'Atribut produk berhasil diperbarui.');
    }
}

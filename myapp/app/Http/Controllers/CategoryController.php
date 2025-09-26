<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    // Pagination untuk function index()
    public function index()
    {
        // dd(auth()->user()->role); // untuk debug
        $categories = Category::paginate(5);  // tampil 5 per halaman
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        // Category::create($request->validated());
        // return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
        try {
            Category::create($request->validated());
            return redirect()->route('categories.index')
                ->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->withErrors(['name' => 'Kategori sudah ada, tidak boleh duplikat.'])
                ->withInput();
        }
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function show(Category $category)
    {
        // Kalau nggak ada halaman detail, langsung redirect balik
        return redirect()->route('categories.index');
    }
}

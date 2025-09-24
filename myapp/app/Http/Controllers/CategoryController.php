<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
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

    // public function index()
    // {
    //     $categories = Category::all();
    //     return view('categories.index', compact('categories'));
    // }

    // Pagination untuk function index()
    public function index()
    {
        $categories = Category::paginate(5);  // tampil 5 per halaman
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'description' => 'nullable'
    //     ]);

    //     Category::create($request->all());
    //     return redirect()->route('categories.index')->with('success', 'Category created.');
    // }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|unique:categories,name',
    //         'description' => 'nullable'
    //     ]);

    //     Category::create($request->all());
    //     return redirect()->route('categories.index')->with('success', 'Category created.');
    // }
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

    // public function update(Request $request, Category $category)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'description' => 'nullable'
    //     ]);

    //     $category->update($request->all());
    //     return redirect()->route('categories.index')->with('success', 'Category updated.');
    // }
    // public function update(Request $request, Category $category)
    // {
    //     $request->validate([
    //         'name' => 'required|unique:categories,name,' . $category->id,
    //         'description' => 'nullable'
    //     ]);

    //     $category->update($request->all());
    //     return redirect()->route('categories.index')->with('success', 'Category updated.');
    // }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }

    public function show(Category $category)
    {
        // Kalau nggak ada halaman detail, langsung redirect balik
        return redirect()->route('categories.index');
    }
}

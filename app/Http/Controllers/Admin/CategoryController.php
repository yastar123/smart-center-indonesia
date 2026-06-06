<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // return JSON list for AJAX
        $q = Category::query();
        if ($s = $request->search) {
            $q->where('name', 'like', "%$s%");
        }
        $items = $q->orderBy('name')->get();
        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:120|unique:categories,slug',
            'description' => 'nullable|string',
        ]);

        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);

        $cat = Category::create($data);

        return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan', 'data' => $cat]);
    }

    public function show(Category $category)
    {
        return response()->json(['data' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:120|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
        ]);

        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui', 'data' => $category]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus']);
    }
}

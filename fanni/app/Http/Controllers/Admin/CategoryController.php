<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')
            ->when($request->filled('q'), function ($q) use ($request) {
                $s = $request->q;
                $q->where(function ($sub) use ($s) {
                    $sub->where('title', 'like', "%{$s}%")
                        ->orWhere('id', 'like', "%{$s}%");
                });
            })
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'string', 'max:50', 'unique:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
            'img' => ['required', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
            'img' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('img')) {
            if ($category->img) {
                Storage::disk('public')->delete($category->img);
            }
            $data['img'] = $request->file('img')->store('categories', 'public');
        } else {
            unset($data['img']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->img) {
            Storage::disk('public')->delete($category->img);
        }
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}

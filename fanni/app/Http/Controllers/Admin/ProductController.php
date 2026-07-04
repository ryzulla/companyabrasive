<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), function ($q) use ($request) {
                $s = $request->q;
                $q->where(function ($sub) use ($s) {
                    $sub->where('title', 'like', "%{$s}%")
                        ->orWhere('meta', 'like', "%{$s}%")
                        ->orWhere('category_id', 'like', "%{$s}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'meta' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:50'],
            'img' => ['required', 'image', 'max:2048'],
            'desc' => ['required', 'string'],
            'spec_label' => ['required', 'string', 'max:100'],
            'spec_val' => ['required', 'string', 'max:100'],
            'rpm' => ['nullable', 'string', 'max:100'],
            'material' => ['required', 'string', 'max:255'],
        ]);

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'meta' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:50'],
            'img' => ['nullable', 'image', 'max:2048'],
            'desc' => ['required', 'string'],
            'spec_label' => ['required', 'string', 'max:100'],
            'spec_val' => ['required', 'string', 'max:100'],
            'rpm' => ['nullable', 'string', 'max:100'],
            'material' => ['required', 'string', 'max:255'],
        ]);

        if ($request->hasFile('img')) {
            if ($product->img) {
                Storage::disk('public')->delete($product->img);
            }
            $data['img'] = $request->file('img')->store('products', 'public');
        } else {
            unset($data['img']);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->img) {
            Storage::disk('public')->delete($product->img);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}

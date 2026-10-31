<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
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
            ->get();

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
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['image', 'max:2048'],
            'desc' => ['required', 'string'],
            'spec_label' => ['required', 'string', 'max:100'],
            'spec_val' => ['required', 'string', 'max:100'],
            'rpm' => ['nullable', 'string', 'max:100'],
            'material' => ['required', 'string', 'max:255'],
        ]);

        unset($data['images']);
        $data['img'] = '';
        $product = Product::create($data);
        $this->addImages($product, $request->file('images'));

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
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'max:2048'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
            'desc' => ['required', 'string'],
            'spec_label' => ['required', 'string', 'max:100'],
            'spec_val' => ['required', 'string', 'max:100'],
            'rpm' => ['nullable', 'string', 'max:100'],
            'material' => ['required', 'string', 'max:255'],
        ]);

        $remove = $product->images()->whereIn('id', $request->input('remove_images', []))->get();
        $newFiles = $request->file('images', []);

        if ($product->images()->count() - $remove->count() + count($newFiles) < 1) {
            throw ValidationException::withMessages(['images' => 'Produk harus memiliki minimal 1 foto.']);
        }

        unset($data['images'], $data['remove_images']);
        $product->update($data);

        foreach ($remove as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $this->addImages($product, $newFiles);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function addImages(Product $product, array $files): void
    {
        $sort = (int) $product->images()->max('sort');

        foreach ($files as $file) {
            $product->images()->create([
                'path' => $file->store('products', 'public'),
                'sort' => ++$sort,
            ]);
        }

        $product->syncCover();
    }
}

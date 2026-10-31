<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Support\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%")
                  ->orWhere('meta', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        $paginated = $query->latest()->paginate(12)->withQueryString();

        $products = $paginated->getCollection()->map(fn($p) => [
            'id'            => $p->id,
            'url'           => route('products.show', $p, false),
            'type'          => $p->category_id,
            'meta'          => $p->meta,
            'title'         => $p->title,
            'badge'         => $p->badge ?? '',
            'img'           => $p->img ? Storage::url($p->img) : '',
            'desc'          => $p->desc,
            'specLabel'     => $p->spec_label,
            'specVal'       => $p->spec_val,
            'rpm'           => $p->rpm ?? '-',
            'material'      => $p->material,
            'categoryTitle' => $p->category?->title ?? '',
        ]);

        return response()->json([
            'products'    => $products,
            'total'       => $paginated->total(),
            'currentPage' => $paginated->currentPage(),
            'lastPage'    => $paginated->lastPage(),
            'from'        => $paginated->firstItem() ?? 0,
            'to'          => $paginated->lastItem() ?? 0,
        ]);
    }

    public function index(Request $request)
    {
        $s          = Setting::allKeyed();
        $categories = Category::all();

        $query = Product::with('category');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%")
                  ->orWhere('meta', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $productsJson = $products->getCollection()->map(fn($p) => [
            'id'        => $p->id,
            'url'       => route('products.show', $p, false),
            'type'      => $p->category_id,
            'meta'      => $p->meta,
            'title'     => $p->title,
            'badge'     => $p->badge ?? '',
            'img'       => $p->img ? Storage::url($p->img) : '',
            'desc'      => $p->desc,
            'specLabel' => $p->spec_label,
            'specVal'   => $p->spec_val,
            'rpm'       => $p->rpm ?? '-',
            'material'  => $p->material,
        ]);

        if (Device::isMobile($request)) {
            return response()
                ->view('home.products-mobile', compact('s', 'categories', 'products'))
                ->header('Vary', 'User-Agent');
        }

        return response()
            ->view('home.products', compact('s', 'categories', 'products', 'productsJson'))
            ->header('Vary', 'User-Agent');
    }

    /** URL lama /products/{id} dialihkan permanen (301) ke URL slug. */
    public function legacyRedirect(int $id)
    {
        $product = Product::findOrFail($id);

        return redirect()->route('products.show', $product, 301);
    }

    public function sitemap()
    {
        $urls = collect([
            ['loc' => route('home'), 'lastmod' => null, 'priority' => '1.0'],
            ['loc' => route('about'), 'lastmod' => null, 'priority' => '0.6'],
            ['loc' => route('products.index'), 'lastmod' => null, 'priority' => '0.9'],
            ['loc' => route('blog.index'), 'lastmod' => null, 'priority' => '0.7'],
        ])->concat(
            Blog::orderBy('id')->get()->map(fn($b) => [
                'loc'      => route('blog.show', $b),
                'lastmod'  => $b->updated_at?->toAtomString(),
                'priority' => '0.6',
            ])
        )->concat(
            Product::orderBy('id')->get()->map(fn($p) => [
                'loc'      => route('products.show', $p),
                'lastmod'  => $p->updated_at?->toAtomString(),
                'priority' => '0.8',
            ])
        );

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    public function show(Request $request, Product $product)
    {
        $s = Setting::allKeyed();
        $product->load(['category', 'images']);

        $images = $product->images->map(fn($i) => Storage::url($i->path))->values();
        if ($images->isEmpty() && $product->img) {
            $images = collect([Storage::url($product->img)]);
        }

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(4)
            ->get();

        $view = Device::isMobile($request) ? 'home.product-show-mobile' : 'home.product-show';

        // Satu URL, dua tampilan (desktop/mobile) → beri tahu cache & mesin pencari
        return response()
            ->view($view, compact('s', 'product', 'images', 'related'))
            ->header('Vary', 'User-Agent');
    }
}

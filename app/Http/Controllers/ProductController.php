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

        // Halaman kategori (/products?category=...) = landing page yang boleh diindeks.
        // Hasil pencarian & halaman 2 dst. tidak diindeks, tapi tautannya tetap diikuti.
        $activeCat = $request->filled('category') ? $categories->firstWhere('id', $request->category) : null;
        $company   = $s['company_name'] ?? 'Company Profile';
        $canonical = route('products.index', array_filter(['category' => $activeCat?->id]));
        $thin      = $request->filled('q') || $products->currentPage() > 1
            || ($request->filled('category') && $request->category !== 'all' && ! $activeCat)
            || $products->total() === 0;
        $heading   = $activeCat ? $activeCat->title : 'Katalog Produk';

        $crumbs = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Katalog Produk', 'item' => route('products.index')],
        ];
        if ($activeCat) {
            $crumbs[] = ['@type' => 'ListItem', 'position' => 3, 'name' => $activeCat->title, 'item' => $canonical];
        }

        $seo = [
            'title'       => ($activeCat ? 'Jual ' . $activeCat->title : 'Katalog Produk Abrasive') . ' | ' . $company,
            'description' => $activeCat
                ? trim(($activeCat->desc ?: $activeCat->title) . ' Tersedia di ' . $company . ', hubungi kami untuk info stok dan harga.')
                : ($s['products_desc'] ?? 'Katalog amplas, batu gerinda, roda poles, dan perlengkapan industri.'),
            'canonical'   => $canonical,
            'robots'      => $thin ? 'noindex, follow' : null,
            'jsonld'      => [
                ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $crumbs],
                [
                    '@context' => 'https://schema.org',
                    '@type'    => 'CollectionPage',
                    'name'     => $heading,
                    'url'      => $canonical,
                    'isPartOf' => ['@type' => 'WebSite', 'url' => url('/')],
                    'mainEntity' => [
                        '@type' => 'ItemList',
                        'numberOfItems' => $products->total(),
                        'itemListElement' => $products->getCollection()->values()->map(fn($p, $i) => [
                            '@type' => 'ListItem', 'position' => $i + 1,
                            'name' => $p->title, 'url' => route('products.show', $p),
                        ])->all(),
                    ],
                ],
            ],
        ];

        if (Device::isMobile($request)) {
            return response()
                ->view('home.products-mobile', compact('s', 'categories', 'products', 'seo', 'heading'))
                ->header('Vary', 'User-Agent');
        }

        return response()
            ->view('home.products', compact('s', 'categories', 'products', 'productsJson', 'seo', 'heading'))
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
        $abs = fn(?string $path) => $path ? url(Storage::url($path)) : null;
        $productsMod = Product::max('updated_at');
        $blogsMod    = Blog::max('updated_at');
        $iso = fn($d) => $d ? \Illuminate\Support\Carbon::parse($d)->toAtomString() : null;

        $urls = collect([
            ['loc' => route('home'), 'lastmod' => $iso(max($productsMod, $blogsMod)), 'priority' => '1.0'],
            ['loc' => route('about'), 'lastmod' => null, 'priority' => '0.6'],
            ['loc' => route('products.index'), 'lastmod' => $iso($productsMod), 'priority' => '0.9'],
            ['loc' => route('blog.index'), 'lastmod' => $iso($blogsMod), 'priority' => '0.7'],
        ])->concat(
            // Halaman kategori (hanya yang ada produknya)
            Category::withMax('products', 'updated_at')->has('products')->orderBy('title')->get()->map(fn($c) => [
                'loc'      => route('products.index', ['category' => $c->id]),
                'lastmod'  => $iso($c->products_max_updated_at),
                'priority' => '0.8',
            ])
        )->concat(
            Blog::orderBy('id')->get()->map(fn($b) => [
                'loc'      => route('blog.show', $b),
                'lastmod'  => $b->updated_at?->toAtomString(),
                'priority' => '0.6',
                'images'   => array_filter([$abs($b->img)]),
            ])
        )->concat(
            Product::with('images')->orderBy('id')->get()->map(fn($p) => [
                'loc'      => route('products.show', $p),
                'lastmod'  => $p->updated_at?->toAtomString(),
                'priority' => '0.8',
                'images'   => $p->images->pluck('path')->whenEmpty(fn($c) => $c->push($p->img))
                    ->filter()->take(10)->map($abs)->values()->all(),
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

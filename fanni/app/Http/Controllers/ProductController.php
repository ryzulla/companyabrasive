<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
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

        return view('home.products', compact('s', 'categories', 'products', 'productsJson'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Client;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Video;
use App\Support\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $s = Setting::allKeyed();

        $categories = Category::withCount('products')->orderBy('title')->get();

        // Mobile menampilkan 12 produk pertama (= halaman 1 pencarian), lalu "Muat Lebih Banyak" mengambil halaman berikutnya.
        $mobile = Device::isMobile($request);
        $productsTotal = Product::count();
        $products = Product::with('category')->latest()->take($mobile ? 12 : 8)->get()->map(function ($p) {
            return [
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
            ];
        });

        $videos      = Video::all();
        $clients     = Client::orderBy('order')->orderBy('name')->get();
        $testimonials = Testimonial::all();
        $faqs        = Faq::all();
        $blogs       = Blog::latest()->get();

        // Dari tombol "Minta Penawaran" di halaman produk
        $prefill = null;
        if ($request->filled('produk')) {
            $title = Product::where('slug', $request->query('produk'))->value('title');
            if ($title) {
                $prefill = [
                    'subject' => 'Permintaan Penawaran',
                    'message' => "Halo, saya ingin meminta penawaran untuk produk {$title}.\n\nKebutuhan/jumlah: ",
                ];
            }
        }

        $view = $mobile ? 'home.mobile' : 'home.index';

        // Satu URL, dua tampilan (desktop/mobile) → beri tahu cache & mesin pencari
        return response()->view($view, compact(
            's', 'categories', 'products', 'productsTotal', 'videos',
            'clients', 'testimonials', 'faqs', 'blogs', 'prefill'
        ))->header('Vary', 'User-Agent');
    }
}

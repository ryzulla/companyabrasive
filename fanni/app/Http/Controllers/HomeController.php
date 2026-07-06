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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $s = Setting::allKeyed();

        $categories = Category::all();

        $products = Product::with('category')->latest()->take(8)->get()->map(function ($p) {
            return [
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
            ];
        });

        $videos      = Video::all();
        $clients     = Client::orderBy('order')->orderBy('name')->get();
        $testimonials = Testimonial::all();
        $faqs        = Faq::all();
        $blogs       = Blog::latest()->take(3)->get();

        $view = $this->isMobile($request) ? 'home.mobile' : 'home.index';

        return view($view, compact(
            's', 'categories', 'products', 'videos',
            'clients', 'testimonials', 'faqs', 'blogs'
        ));
    }

    /**
     * Deteksi perangkat mobile via User-Agent.
     * Bisa dipaksa untuk pengujian: ?view=mobile atau ?view=desktop.
     */
    private function isMobile(Request $request): bool
    {
        if ($request->query('view') === 'mobile') {
            return true;
        }
        if ($request->query('view') === 'desktop') {
            return false;
        }

        $ua = (string) $request->header('User-Agent');

        if ($ua === '' || preg_match('/iPad|Tablet|PlayBook|Silk|Kindle/i', $ua)) {
            return false; // tablet & UA kosong → tampilan desktop
        }

        return (bool) preg_match(
            '/Mobile|Android.+Mobile|iPhone|iPod|BlackBerry|IEMobile|Opera Mini|webOS|Windows Phone/i',
            $ua
        );
    }
}

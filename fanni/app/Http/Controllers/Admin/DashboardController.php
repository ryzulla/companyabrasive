<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Client;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Video;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'categories' => Category::count(),
            'products' => Product::count(),
            'videos' => Video::count(),
            'faqs' => Faq::count(),
            'testimonials' => Testimonial::count(),
            'blogs' => Blog::count(),
            'messages' => ContactMessage::count(),
            'clients'  => Client::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

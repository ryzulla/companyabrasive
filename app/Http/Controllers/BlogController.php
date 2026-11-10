<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Setting;
use App\Support\Device;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $s = Setting::allKeyed();
        $blogs = Blog::latest()->paginate(9)->withQueryString();

        $view = Device::isMobile($request) ? 'home.blog.index-mobile' : 'home.blog.index';

        return response()->view($view, compact('s', 'blogs'))->header('Vary', 'User-Agent');
    }

    public function show(Request $request, Blog $blog)
    {
        $s = Setting::allKeyed();
        $related = Blog::whereKeyNot($blog->id)->latest()->take(3)->get();

        $view = Device::isMobile($request) ? 'home.blog.show-mobile' : 'home.blog.show';

        return response()->view($view, compact('s', 'blog', 'related'))->header('Vary', 'User-Agent');
    }
}

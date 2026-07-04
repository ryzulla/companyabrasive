<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::when($request->filled('q'), function ($q) use ($request) {
                $s = $request->q;
                $q->where(function ($sub) use ($s) {
                    $sub->where('title', 'like', "%{$s}%")
                        ->orWhere('meta', 'like', "%{$s}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'img' => ['required', 'image', 'max:2048'],
            'meta' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
        ]);

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog berhasil ditambahkan.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'img' => ['nullable', 'image', 'max:2048'],
            'meta' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
        ]);

        if ($request->hasFile('img')) {
            if ($blog->img) {
                Storage::disk('public')->delete($blog->img);
            }
            $data['img'] = $request->file('img')->store('blogs', 'public');
        } else {
            unset($data['img']);
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog berhasil diperbarui.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->img) {
            Storage::disk('public')->delete($blog->img);
        }
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog berhasil dihapus.');
    }
}

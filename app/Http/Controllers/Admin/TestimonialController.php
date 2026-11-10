<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialInvite;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $testimonials = Testimonial::when($request->filled('q'), function ($q) use ($request) {
                $s = $request->q;
                $q->where(function ($sub) use ($s) {
                    $sub->where('author', 'like', "%{$s}%")
                        ->orWhere('text', 'like', "%{$s}%");
                });
            })
            ->latest()
            ->get();

        $invites = TestimonialInvite::latest()->get();

        return view('admin.testimonials.index', compact('testimonials', 'invites'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'text' => ['required', 'string'],
            'author' => ['required', 'string', 'max:100'],
            'pos' => ['required', 'string', 'max:100'],
        ]);

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'text' => ['required', 'string'],
            'author' => ['required', 'string', 'max:100'],
            'pos' => ['required', 'string', 'max:100'],
        ]);

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimoni berhasil dihapus.');
    }

    /** Buat link undangan sekali pakai untuk dikirim ke klien. */
    public function storeInvite(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:150'],
            'days'  => ['nullable', 'integer', 'in:7,14,30,90'],
        ]);

        $invite = TestimonialInvite::generate($data['label'], $data['days'] ?? null);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Link undangan dibuat. Salin dan kirim ke klien.')
            ->with('new_invite', $invite->id);
    }

    public function destroyInvite(TestimonialInvite $invite)
    {
        $invite->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Link undangan dicabut. Link tersebut tidak bisa dipakai lagi.');
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['status' => Testimonial::APPROVED]);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimoni disetujui dan sekarang tampil di website.');
    }
}

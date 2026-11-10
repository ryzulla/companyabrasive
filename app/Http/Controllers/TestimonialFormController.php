<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\TestimonialInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

/** Halaman privat tempat klien mengisi testimoni lewat link undangan sekali pakai. */
class TestimonialFormController extends Controller
{
    public function show(string $token)
    {
        $s = Setting::allKeyed();
        $invite = TestimonialInvite::where('token', $this->normalize($token))->first();

        $state = match (true) {
            ! $invite => 'invalid',
            $invite->isUsed() => 'used',
            $invite->isExpired() => 'expired',
            default => 'form',
        };

        return response()
            ->view('testimonial.form', compact('s', 'invite', 'state'), $state === 'form' ? 200 : 410)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function store(Request $request, string $token)
    {
        // Honeypot: bot mengisi kolom tersembunyi → pura-pura sukses
        if ($request->filled('website')) {
            return redirect()->route('testimonial.thanks');
        }

        $data = $request->validate([
            'author'  => ['required', 'string', 'max:100'],
            'pos'     => ['required', 'string', 'max:100'],
            'text'    => ['required', 'string', 'min:20', 'max:1000'],
            'consent' => ['accepted'],
        ], [
            'text.min'         => 'Testimoni minimal 20 karakter.',
            'text.max'         => 'Testimoni maksimal 1000 karakter.',
            'consent.accepted' => 'Centang persetujuan agar testimoni dapat ditampilkan.',
        ]);

        // Kunci baris undangan supaya link tidak bisa dipakai dua kali (mis. klik ganda)
        $testimonial = DB::transaction(function () use ($token, $data) {
            $invite = TestimonialInvite::where('token', $this->normalize($token))->lockForUpdate()->first();
            if (! $invite || ! $invite->isUsable()) {
                return null;
            }

            $testimonial = Testimonial::create([
                'text' => trim($data['text']),
                'author' => trim($data['author']),
                'pos' => trim($data['pos']),
                'status' => Testimonial::PENDING,
            ]);
            $invite->update(['used_at' => now(), 'testimonial_id' => $testimonial->id]);

            return $testimonial;
        });

        if (! $testimonial) {
            return redirect()->route('testimonial.form', $this->normalize($token));
        }

        $this->notifyAdmin($testimonial);

        return redirect()->route('testimonial.thanks');
    }

    /** Kode baru (10 karakter) tidak peka huruf besar/kecil; kode lama yang panjang dibiarkan apa adanya. */
    private function normalize(string $token): string
    {
        return strlen($token) <= 12 ? strtoupper($token) : $token;
    }

    public function thanks()
    {
        $s = Setting::allKeyed();

        return response()
            ->view('testimonial.form', ['s' => $s, 'invite' => null, 'state' => 'thanks'])
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    private function notifyAdmin(Testimonial $testimonial): void
    {
        $to = Setting::allKeyed()['email'] ?? null;
        if (! $to) {
            return;
        }

        try {
            Mail::raw(
                "Testimoni baru menunggu persetujuan.\n\nNama: {$testimonial->author}\nJabatan/Perusahaan: {$testimonial->pos}\n\n{$testimonial->text}\n\nTinjau di admin: " . route('admin.testimonials.index'),
                fn ($m) => $m->to($to)->subject('Testimoni baru dari ' . $testimonial->author)
            );
        } catch (Throwable $e) {
            report($e);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Throwable;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $success = 'Terima kasih! Permintaan Anda telah kami terima. Tim sales kami akan segera menghubungi Anda.';

        // Honeypot: field tersembunyi hanya diisi bot → pura-pura sukses tanpa menyimpan
        if ($request->filled('website')) {
            return back()->with('contact_success', $success);
        }

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:150'],
            'phone'   => ['required', 'string', 'max:30'],
            'subject' => ['nullable', 'string', Rule::in(ContactMessage::SUBJECTS)],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $contact = ContactMessage::create($data);

        $this->notifyAdmin($contact);

        return back()->with('contact_success', $success);
    }

    /** Beri tahu tim sales lewat email; kegagalan kirim tidak boleh membatalkan pesan yang sudah tersimpan. */
    private function notifyAdmin(ContactMessage $contact): void
    {
        $to = Setting::allKeyed()['email'] ?? null;
        if (! $to) {
            return;
        }

        try {
            $body = "Pesan baru dari website\n\n"
                . "Nama    : {$contact->name}\n"
                . "Email   : {$contact->email}\n"
                . "Telepon : {$contact->phone}\n"
                . 'Subjek  : ' . ($contact->subject ?: '-') . "\n\n"
                . $contact->message . "\n\n"
                . 'Lihat di admin: ' . route('admin.contacts.show', $contact);

            Mail::raw($body, function ($m) use ($to, $contact) {
                $m->to($to)
                  ->replyTo($contact->email, $contact->name)
                  ->subject(($contact->subject ?: 'Pesan baru') . ' dari ' . $contact->name);
            });
        } catch (Throwable $e) {
            report($e);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::when($request->filled('q'), function ($q) use ($request) {
                $s = $request->q;
                $q->where(function ($sub) use ($s) {
                    $sub->where('name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%")
                        ->orWhere('message', 'like', "%{$s}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.contacts.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $s = Setting::allKeyed();

        return view('admin.contacts.show', compact('contactMessage', 's'));
    }

    public function replyEmail(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'body'    => ['required', 'string'],
        ]);

        try {
            Mail::raw($data['body'], function ($m) use ($contactMessage, $data) {
                $m->to($contactMessage->email, $contactMessage->name)->subject($data['subject']);
                if ($replyTo = Setting::allKeyed()['email'] ?? null) {
                    $m->replyTo($replyTo);
                }
            });
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Email gagal dikirim: ' . $e->getMessage());
        }

        return back()->with('success', 'Balasan email terkirim ke ' . $contactMessage->email . '.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}

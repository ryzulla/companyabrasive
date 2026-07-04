<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email'],
            'phone'   => ['required', 'string', 'max:30'],
            'message' => ['required', 'string'],
        ]);

        ContactMessage::create($request->only('name', 'email', 'phone', 'message'));

        return back()->with('contact_success', 'Terima kasih! Permintaan Anda telah kami terima. Tim sales kami akan segera menghubungi Anda.');
    }
}

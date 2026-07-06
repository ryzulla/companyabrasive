<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        $s = Setting::allKeyed();

        return view('home.about', compact('s'));
    }
}

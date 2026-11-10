<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\Device;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index(Request $request)
    {
        $s = Setting::allKeyed();

        $view = Device::isMobile($request) ? 'home.about-mobile' : 'home.about';

        return response()->view($view, compact('s'))->header('Vary', 'User-Agent');
    }
}

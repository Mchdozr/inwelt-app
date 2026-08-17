<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    public function editorial(): View
    {
        return view('pages.legal.editorial');
    }

    public function privacy(): View
    {
        return view('pages.legal.privacy');
    }

    public function kvkk(): View
    {
        return view('pages.legal.kvkk');
    }

    public function terms(): View
    {
        return view('pages.legal.terms');
    }

    public function cookies(): View
    {
        return view('pages.legal.cookies');
    }
}

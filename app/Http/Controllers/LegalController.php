<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function impressum()
    {
        return view('legal.impressum');
    }

    public function privacy()
    {
        return view('legal.privacy');
    }

    public function terms()
    {
        return view('legal.terms');
    }
}

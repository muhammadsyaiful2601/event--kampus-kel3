<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch($locale)
    {
        if (in_array($locale, ['en', 'id'])) {
            session()->put('locale', $locale);
            // Also save to cookie so it persists after logout and across browser sessions
            cookie()->queue(cookie()->forever('locale', $locale));
        }

        return redirect()->back();
    }
}
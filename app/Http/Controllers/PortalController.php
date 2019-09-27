<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        $lastNews = App\News::orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return view('portal.index', compact('lastNews'));
    }

    public function services()
    {
        return view('portal.services');
    }

    public function contacts()
    {
        return view('portal.contacts');
    }

    public function news()
    {
        $lastNews = App\News::orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return view('portal.news', compact('lastNews'));
    }
}

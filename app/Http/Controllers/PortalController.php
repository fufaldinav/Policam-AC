<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        return view('portal.index');
    }

    public function services()
    {
        return view('portal.services');
    }

    public function prices()
    {
        return view('portal.prices');
    }

    public function support()
    {
        return view('portal.support');
    }

    public function news()
    {
        $lastNews = App\News::orderBy('created_at', 'ASC')
            ->limit(5)
            ->get();
        return view('portal.news', compact('lastNews'));
    }
}

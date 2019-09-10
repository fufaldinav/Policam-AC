<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getEntry($id)
    {
        $entry = App\News::find($id);

        abort_if(is_null($entry), 404);

        $lastNews = App\News::where('id', '<>', $entry->id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return view('portal.newsEntry', compact('entry', 'lastNews'));
    }
}

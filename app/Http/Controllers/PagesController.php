<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {

    }

    public function getEntry($id) {
        $entry = App\Page::find($id);

        abort_if(is_null($entry), 404);

        return view('portal.pages', compact('entry'));
    }
}

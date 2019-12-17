<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class AdminPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
        $this->middleware('role:6');
    }

    public function index()
    {
        return view('admin.index');
    }

    public function getControllers()
    {
        $controllers = App\Controller::with(['devices'])->get();

        return response()->json($controllers);
    }
}

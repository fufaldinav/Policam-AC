<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Policam\Ac\Z5RWEB;

class ServersController extends Controller
{
    /**
     * @param Request $request
     *
     * @return string|null
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = new Z5RWEB\Request($request->getContent());
        return $handler->handle();
    }
}

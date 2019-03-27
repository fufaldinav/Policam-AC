<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;

class ObserverController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        /* Подразделения */
        $divs = $org
            ->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get();

        $data = [
            'divs' => $divs,
            'org_name' => $org->name ?? __('ac/common.missing'),
            'css_list' => ['ac'],
            'js_list' => ['main', 'observer']
        ];


        return view('ac.observer', $data);
    }
}

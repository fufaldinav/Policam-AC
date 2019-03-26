<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;

class ObserverController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        $user = App\User::find($user->id);

        $org = $user->organizations()->first();

        /*
        | Подразделения
        */
        $divs = $org
            ->divisions()
            ->orderBy('type')
            ->orderByRaw('CAST(name AS UNSIGNED) ASC')
            ->orderBy('name')
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

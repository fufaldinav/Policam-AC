<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;

class DivisionsController extends Controller
{
    public function classes()
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        $user = App\User::find($user->id);

        $org = $user->organizations()->first();

        $divs = $org->divisions()
            ->orderBy('type')
            ->orderByRaw('CAST(name AS UNSIGNED) ASC')
            ->orderBy('name')
            ->get();

        $org_id = $org->id;
        $divs = $divs;

        $org_name = $org->name ?? __('ac/common.missing');
        $css_list = ['ac', 'tables'];
        $js_list = ['classes'];

        return view('ac.classes', compact('org_id', 'divs', 'org_name', 'css_list', 'js_list'));
    }

    public function getList()
    {
        $user = Auth::user();

        if (! isset($user)) {
            return;
        }

        $user = App\User::find($user->id);

        $divs = [];

        foreach ($user->organizations as $org) {
            $divs = array_merge(
                $divs,
                $org->divisions()
                    ->orderBy('type')
                    ->orderByRaw('CAST(name AS UNSIGNED) ASC')
                    ->orderBy('name')
                    ->get()
            );
        }

//        header('Content-Type: application/json');

        return json_encode($divs);
    }

    public function add(Request $request)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return;
        }

        $div_data = json_decode($request->input('div'));

        $div = App\Division::create($div_data);

//        header('Content-Type: application/json');

        return json_encode($div);
    }

    public function delete(int $div_id = null)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        if (is_null($div_id)) {
            echo 0;
            exit;
        }

        $user = App\User::find($user->id);

        $org = $user->organizations()->first();

        $cur_div = App\Division::find($div_id);

        //"Пустое" подразделение
        $empty_div = App\Division::where('org_id', $org->id)
            ->where('type', 0)
            ->first();

        //Переносим полученных людей в "пустое" подразделение
        foreach ($cur_div->persons as $person) {
            $person->division()->detach($cur_div->id);

            if (! $person->divisions) {
                $person->division()->attach($empty_div->id);
            }
        }

        return $cur_div->delete();
    }
}

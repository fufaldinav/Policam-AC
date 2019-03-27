<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;

class DivisionsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classes()
    {
        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        $divs = $org->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get();

        $org_id = $org->id;

        $org_name = $org->name ?? __('ac/common.missing');
        $css_list = ['ac', 'tables'];
        $js_list = ['classes'];

        return view('ac.classes', compact(
            'org_id',
            'divs',
            'org_name',
            'css_list',
            'js_list'
        ));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList()
    {
        $user = App\User::find(Auth::id());

        $divs = [];

        foreach ($user->organizations as $org) {
            $cur_div = $org->divisions()
                ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
                ->get()
                ->toArray();

            $divs = array_merge($divs, $cur_div);
        }

        return response()->json($divs);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $div_data = json_decode($request->input('div'), true);

        $div = App\Division::create($div_data);

        return response()->json($div);
    }

    /**
     * @param int|null $div_id
     *
     * @return int
     * @throws \Exception
     */
    public function delete(int $div_id = null)
    {
        if (is_null($div_id)) {
            return 0;
        }

        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        $cur_div = App\Division::find($div_id);

        //"Пустое" подразделение
        $empty_div = App\Division::where('organization_id', $org->id)
            ->where('type', 0)
            ->first();

        //Переносим полученных людей в "пустое" подразделение
        foreach ($cur_div->persons as $person) {
            $person->divisions()->detach($cur_div->id);

            if ($person->divisions->count() == 0) {
                $person->divisions()->attach($empty_div->id);
            }
        }

        return (int)$cur_div->delete();
    }
}

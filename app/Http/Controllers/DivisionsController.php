<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 28.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */

namespace App\Http\Controllers;

use App, Auth;
use Illuminate\Http\Request;

class DivisionsController extends Controller
{
    /**
     * Страница управления классами
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classes()
    {
        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        if (! $org) {
            return 'Огранизации отсутствуют';
        }

        $divs = $org->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get();

        $org_id = $org->id;

        $org_name = $org->name ?? __('ac/common.missing');
        $css_list = ['tables'];
        $js_list = ['classes'];

        return view('ac.classes', compact(
            'org_id',
            'divs',
            'org_name',
            'css_list',
            'js_list',
        ));
    }

    /**
     * Получает коллекцию подразделений
     *
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
     * Сохраняет подразделение
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $div_data = json_decode($request->input('div'), true);

        $div = App\Division::create($div_data);

        return response()->json($div);
    }

    /**
     * Удаляет подразделение
     *
     * @param Request $request
     *
     * @return int
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $div_id = $request->input('div_id');

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

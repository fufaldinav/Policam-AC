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

class ObserverController extends Controller
{
    /**
     * Страница наблюдения
     *
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

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

use App;
use Illuminate\Http\Request;

class ObserverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Страница наблюдения
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $org = $request->user()->organizations()->first();

        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        /* Подразделения */
        $divs = $org
            ->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get();

        $org_name = $org->name ?? __('ac.missing');
        $js_list = ['main', 'observer'];

        return view('ac.observer', compact('divs', 'org_name', 'js_list'));
    }
}

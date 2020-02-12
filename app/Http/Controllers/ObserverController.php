<?php
/**
 * Name:   Policam AC
 *
 * Created: 28.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
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
     * @param int|null $organization_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $org = $request->user()->organizations()->first();
        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        return view('ac.observer');
    }
}

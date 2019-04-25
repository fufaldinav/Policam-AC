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

class DivisionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Страница управления классами
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classes(Request $request)
    {
        $org = $request->user()->organizations()->first();

        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        return view('ac.classes');
    }

    public function index(Request $request)
    {
        $organization = $request->user()->organizations()->first();

        abort_if(! $organization, 403);

        return response()->json(
            $organization->divisions->load(['persons.cards', 'persons.photos'])
        );
    }

    public function show(Request $request, $organization_id)
    {
        $divisions = $request->user()->divisions()->where('divisions.organization_id', $organization_id)->get()->load(['persons.cards', 'persons.photos']);

        abort_if(! $divisions, 403);

        return response()->json($divisions);
    }

    public function update(Request $request, $id)
    {
        $division = $request->user()->divisions()->where('divisions.id', $id)->first();

        abort_if(! $division, 403);

        $division->update($request->input('division'));

        return response()->json($division->load('persons'));
    }

    public function store(Request $request)
    {
        $division = App\Division::create($request->input('division'));

        return response()->json($division->load('persons'));
    }

    public function destroy(Request $request, int $id): ?int
    {
        $division = $request->user()->divisions()->where('divisions.id', $id)->first();

        abort_if(! $division, 403);

        if ($division->delete()) {
            return $id;
        }

        return null;
    }
}

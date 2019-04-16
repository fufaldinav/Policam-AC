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

class PersonsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function page(Request $request, int $organization_id = null)
    {
        if ($organization_id) {
            $org = $request->user()->organizations()->where('organization_id', $organization_id)->first();
            abort_if(! $org, 403);
        } else {
            $org = $request->user()->organizations()->first();
            if (! $org) {
                return view('ac.error', ['error' => 'Огранизации отсутствуют']);
            }
        }

        /* Подразделения */
        $divisions = $org
            ->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get()
            ->load(['persons' => function ($query) {
                $query->orderByRaw('f ASC, i ASC, o ASC')->get()->load(['cards', 'photos']);
            }]);

        $org_name = $org->name;

        return view('ac.persons', compact('divisions', 'org_name'));
    }

    public function index(Request $request)
    {
        $persons = $request->user()->persons()->get();

        abort_if(! $persons, 403);

        return response()->json($persons);
    }

    public function show(Request $request, $id)
    {
        $person = $request->user()->persons()->where('persons.id', $id)->first()->load(['photos', 'cards']);

        abort_if(! $person, 403);

        return response()->json($person);
    }

    public function update(Request $request, $id)
    {
        $person = $request->user()->persons()->where('persons.id', $id)->first();

        abort_if(! $person, 403);

        $person->update($request->input('person'));

        $person->attachDivisions($request->input('divisions'))
            ->attachCards($request->input('cards'))
            ->attachPhotos($request->input('photos'));

        return response()->json($person->load('divisions'));
    }

    public function store(Request $request)
    {
        $person = App\Person::create($request->input('person'));

        $person->attachDivisions($request->input('divisions'))
            ->attachCards($request->input('cards'))
            ->attachPhotos($request->input('photos'));

        return response()->json($person->load('divisions'));
    }

    public function destroy(Request $request, int $id): ?int
    {
        $person = $request->user()->persons()->where('persons.id', $id)->first();

        abort_if(! $person, 403);

        $person->detachDivisions()->detachCards()->detachPhotos()->detachSubscribers();

        if ($person->delete()) {
            return $id;
        }

        return null;
    }
}

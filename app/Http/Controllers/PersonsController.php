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

    public function page(Request $request)
    {
        $org = $request->user()->organizations()->first();
        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        return view('ac.persons');
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
        $onUpdatePerson = $request->user()->persons()->where('persons.id', $id)->first();

        abort_if(! $onUpdatePerson, 403);

        $person = $request->input('person');

        $cards = $person['cards'];
        $divisions = $person['divisions'];
        $photos = $person['photos'];

        $cardsToDelete = $person['cardsToDelete'];
        $divisionsToDelete = $person['divisionsToDelete'];
        $photosToDelete = $person['photosToDelete'];

        $onUpdatePerson->update($request->input('person'));

        $onUpdatePerson->attachDivisions($divisions)
            ->attachCards($cards)
            ->attachPhotos($photos);

        $onUpdatePerson->detachDivisions($divisionsToDelete)
            ->detachCards($cardsToDelete)
            ->detachPhotos($photosToDelete);

        return response()->json($onUpdatePerson->load(['cards', 'divisions', 'photos']));
    }

    public function store(Request $request)
    {
        $person = $request->input('person');

        $cards = $person['cards'];
        $divisions = $person['divisions'];
        $photos = $person['photos'];

        $person = App\Person::create($person);

        $person->attachDivisions($divisions)
            ->attachCards($cards)
            ->attachPhotos($photos);

        return response()->json($person->load(['cards', 'divisions', 'photos']));
    }

    public function destroy(Request $request, int $id): ?int
    {
        $person = $request->user()->persons()->where('persons.id', $id)->first();

        abort_if(! $person, 403);

        $person->detachAllDivisions()
            ->detachAllCards()
            ->detachAllPhotos()
            ->detachAllSubscribers();

        if ($person->delete()) {
            return $id;
        }

        return null;
    }
}

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
        abort_if(! $request->user()->hasRole([1, 2, 3]), 403);

        $org = $request->user()->organizations()->first();
        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        return view('ac.persons');
    }

    public function index(Request $request)
    {
        abort_if(! $request->user()->hasRole([1, 2, 3]), 403);

        $persons = $request->user()->persons()->get();

        abort_if(! $persons, 403);

        return response()->json($persons);
    }

    public function show(Request $request, $id)
    {
        abort_if(! $request->user()->hasRole([1, 2, 3]), 403);

        $person = $request->user()->persons()->where('persons.id', $id)->first()->load(['photos', 'cards']);

        abort_if(! $person, 403);

        return response()->json($person);
    }

    public function update(Request $request, $id)
    {
        $user = App\User::find($request->user()->id);
        $onUpdatePerson = null;

        abort_if(! $user, 500);

        if ($user->hasRole([4, 5])) {
            $onUpdatePerson = $user->subscriptions()->where('persons.id', $id)->first();
        } elseif ($user->hasRole([1, 2, 3])) {
            $onUpdatePerson = $user->persons()->where('persons.id', $id)->first();
        } else {
            abort(403);
        }

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

        return response()->json($onUpdatePerson->load(['cards', 'divisions', 'photos', 'users']));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        abort_if(! $user->hasRole([1, 2, 3, 4, 5]), 403);

        $person = $request->input('person');

        $cards = $person['cards'];
        $divisions = $person['divisions'];
        $photos = $person['photos'];
        $organizations = $person['organizations'];

        $person = App\Person::create($person);

        $person->attachDivisions($divisions)
            ->attachCards($cards)
            ->attachPhotos($photos)
            ->attachOrganizations($organizations);

        if ($user->hasRole([4])) {
            $person->attachSubscribers([$user->id]);
        }

        return response()->json($person->load(['cards', 'divisions', 'photos', 'users']));
    }

    public function destroy(Request $request, int $id): ?int
    {
        $user = App\User::find($request->user()->id);
        $person = null;

        abort_if(! $user, 500);

        if ($user->hasRole([4, 5])) {
            $person = $user->subscriptions()->where('persons.id', $id)->first();
        } elseif ($user->hasRole([1, 2, 3])) {
            $person = $user->persons()->where('persons.id', $id)->first();
        } else {
            abort(403);
        }

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

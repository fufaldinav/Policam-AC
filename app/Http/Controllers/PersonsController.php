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

class PersonsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function page(Request $request)
    {
        abort_if(! $request->user()->hasRole([1, 2, 3, 7]), 403);

        $org = $request->user()->organizations()->first();
        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        return view('ac.persons');
    }

    public function index(Request $request)
    {
        abort_if(! $request->user()->hasRole([1, 2, 3, 7]), 403);

        $persons = $request->user()->persons()->get();

        abort_if(! $persons, 403);

        return response()->json($persons);
    }

    public function show(Request $request, $id)
    {
        abort_if(! $request->user()->hasRole([1, 2, 3, 7]), 403);

        $person = $request->user()->persons()->where('persons.id', $id)->first()->load(['photos', 'cards']);

        abort_if(! $person, 403);

        return response()->json($person);
    }

    public function update(Request $request, $id)
    {
        $user = App\User::find($request->user()->id);
        $personOnUpdate = null;

        abort_if(! $user, 500);

        if ($user->hasRole([1, 2, 3, 7])) {
            $personOnUpdate = $user->persons()->where('persons.id', $id)->first();
        }

        abort_if(! $personOnUpdate, 403);

        $person = $request->input('person');

        $rc = $person['referral_code'];
        $division = $person['division'];
        $photos = $person['photos'];
        $photosToDelete = $person['photosToDelete'];

        $personOnUpdate->update($request->input('person'));

        $personOnUpdate->attachPhotos($photos)->detachPhotos($photosToDelete);

        $division = $personOnUpdate->moveToDivision($division);

        if (isset($rc['id'])) {
            $rcOnUpdate = App\ReferralCode::find($rc['id']);
            $rcOnUpdate->activated = $rc['activated'];

            $oldRC = $personOnUpdate->referralCode;

            if (isset($oldRC)) {
                if ($rcOnUpdate->id !== $oldRC->id) {
                    $oldRC->activated = 0;
                    $personOnUpdate->detachCard($oldRC->card, $division->organization_id);
                    $oldRC->save();
                }
            }

            if ($rcOnUpdate->activated === 1) {
                $personOnUpdate->attachCard($rcOnUpdate->card, $division->organization_id);
            } else {
                $personOnUpdate->detachCard($rcOnUpdate->card, $division->organization_id);
            }

            $rcOnUpdate->save();
            $rcOnUpdate->persons()->save($personOnUpdate);
        } else if (isset($oldRC)) {
            $oldRC->activated = 0;
            $personOnUpdate->detachCard($oldRC->card, $division->organization_id);
            $oldRC->save();

            $personOnUpdate->referral_code_id = null;
            $personOnUpdate->save();
        }

        return response()->json($personOnUpdate->load(['divisions', 'photos', 'referralCode']));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        abort_if(! $user->hasRole([1, 2, 3, 7]), 403);

        $person = $request->input('person');

        $rc = $person['referral_code'];
        $division = $person['division'];
        $photos = $person['photos'];

        $person = App\Person::create($person);

        $division = $person->moveToDivision($division);
        $person->attachPhotos($photos);

        if (isset($rc['id'])) {
            $rcOnUpdate = App\ReferralCode::find($rc['id']);
            $rcOnUpdate->activated = $rc['activated'];

            if ($rcOnUpdate->activated === 1) {
                $person->attachCard($rcOnUpdate->card, $division->organization_id);
            }

            $rcOnUpdate->save();
            $rcOnUpdate->persons()->save($person);
        }

        return response()->json($person->load(['divisions', 'photos', 'referralCode']));
    }

    public function destroy(Request $request, int $id): ?int
    {
        $user = $request->user();
        $person = null;

        if ($user->hasRole([1, 2, 3, 7])) {
            $person = $user->persons()->where('persons.id', $id)->first();
        }

        abort_if(! $person, 403);

        $rc = $person->referralCode;
        if (isset($rc)) {
            $rc->activated = 0;
            $rc->save();
        }

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

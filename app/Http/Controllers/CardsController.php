<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;

class CardsController extends Controller
{
    public function holder(int $card_id = null, int $person_id = 0)
    {
        if (is_null($card_id)) {
            return 0;
        }

        $user = Auth::user();

        if (! isset($user)) {
            return 0;
        }

        $user = App\User::find($user->id);

        $org = $user->organizations()->first();

        $card = App\Card::find($card_id);
        $card->person_id = $person_id;

        $ctrls = $org->controllers;
        if ($ctrls) {
            if ($card->person_id == 0) {
//                $this->task->delCards([$card->wiegand]);
            } else {
//                $this->task->addCards([$card->wiegand]);
            }

            foreach ($ctrls as $ctrl) {
//                $this->task->add($ctrl->id);
//                $this->task->send();
            }
        }


        return $card->save();
    }

    public function delete(int $card_id = null)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return 0;
        }

        if (is_null($card_id)) {
            return 0;
        }

        $card = App\Card::find($card_id);

        return $card->delete();
    }

    public function getList(int $person_id = 0)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return 0;
        }

        $person = App\Person::find($person_id);

//        header('Content-Type: application/json');

        return json_encode($person->cards);
    }
}

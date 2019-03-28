<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;

class CardsController extends Controller
{
    /**
     * Устанавливает владельца карты
     *
     * @param Request $request
     *
     * @return int
     */
    public function holder(Request $request): int
    {
        $card_id = $request->input('card_id');
        $person_id = $request->input('person_id') ?? 0;

        if (is_null($card_id)) {
            return 0;
        }

        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        $card = App\Card::find($card_id);
        $card->person_id = $person_id;

        $ctrls = $org->controllers;
        if ($ctrls->count() > 0) {
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


        return (int)$card->save();
    }

    /**
     * Удаляет карту
     *
     * @param int|null $card_id
     *
     * @return int
     * @throws \Exception
     */
    public function delete(int $card_id = null): int
    {
        if (is_null($card_id)) {
            return 0;
        }

        $card = App\Card::find($card_id);

        return (int)$card->delete();
    }

    /**
     * Получает список карт
     *
     * @param int $person_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(int $person_id = 0)
    {
        $person = App\Person::find($person_id);

        return response()->json($person->cards);
    }
}

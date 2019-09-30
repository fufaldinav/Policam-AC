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
use App\Policam\Ac\Tasker;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Устанавливает владельца карты
     *
     * @param Request $request
     *
     * @return int
     */
    public function setHolder(Request $request): int
    {
        $card_id = $request->input('card_id');
        $person_id = $request->input('person_id') ?? 0;

        $card = $request->user()->cards->where('cards.id', $card_id)->first();

        abort_if(! $card, 403);

        $card->person_id = $person_id;

        $tasker = new Tasker();

        $ctrls = $request->user()->controllers;

        foreach ($ctrls as $ctrl) {
            if ($card->person_id == 0) {
                $tasker->delCards($ctrl, [$card->wiegand]);
            } else {
                $tasker->addCards($ctrl, [$card->wiegand]);
            }

            $tasker->add($ctrl->id);
            $tasker->send();
        }


        return (int)$card->save();
    }

    /**
     * Удаляет карту
     *
     * @param Request $request
     *
     * @return int
     * @throws \Exception
     */
    public function delete(Request $request): int
    {
        $card_id = $request->input('card_id');

        $card = $request->user()->cards->where('cards.id', $card_id)->first();

        abort_if(! $card, 403);

        return (int)$card->delete();
    }

    /**
     * Получает список карт пользователя
     *
     * @param Request $request
     * @param int $person_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByPerson(Request $request, int $person_id)
    {
        $person = $request->user()->persons->where('id', $person_id)->first();

        if (! $person) {
            abort(403);
        }

        return response()->json($person->cards);
    }

    /**
     * Получает список неизвестных карт
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListOfUnknownCards()
    {
        $cards = App\Card::where('person_id', 0)->get();

        return response()->json($cards);
    }
}

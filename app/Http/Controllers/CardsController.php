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

use App, Auth;
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

        $tasker = new Tasker();

        if ($card->person_id == 0) {
            $tasker->delCards([$card->wiegand]);
        } else {
            $tasker->addCards([$card->wiegand]);
        }

        $ctrls = $org->controllers;

        foreach ($ctrls as $ctrl) {
            $tasker->add($ctrl->id);
            $tasker->send();
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

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
use App\Policam\Ac\Polling;
use App\Policam\Ac\Tasker;
use App\Policam\Ac\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UtilsController extends Controller
{
    /**
     * Возвращает текущее время
     */
    public function getTime()
    {
        return Carbon::now()->toDateTimeString();
    }

    /**
     * Возвращает последние события и реализует long polling
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvents(Request $request)
    {
        $events = $request->input('events');
        $time = $request->input('time');

        $request->session()->save();

        $msgs = Polling::polling($events, $time);

        return response()->json([
            'time' => Carbon::now()->toDateTimeString(),
            'msgs' => $msgs,
        ]);
    }

    /**
     * Сохраняет ошибки от клиентов
     *
     * @param Request $request
     * @param string|null $err Текст ошибки или NULL - получить POST-запрос
     *
     * @return string
     */
    public function saveErrors(Request $request, string $err = null): string
    {
        $err = $err ?? $request->input('error') ?? 'Неизвестная ошибка или ошибка не указана';

        $logger = new Logger();
        $logger->add('err', $err);
        $logger->write();

        return $err;
    }

    /**
     * Обрабатывает проблемы с картами
     *
     * @param Request $request
     *
     * @return string
     */
    public function cardProblem(Request $request): string
    {
        $problem_type = $request->input('type');
        $person_id = $request->input('person_id');

        if (is_null($problem_type) || is_null($person_id)) {
            return null;
        }

        $response = __('ac/common.registered');

        $person = App\Person::find($person_id);

        $cards = $person->cards;

        if (! $cards) {
            return null;
        }

        $description = "{$person->id} {$person->f} {$person->i} ";

        if ($problem_type == 1) {
            $description .= 'forgot card';
        } elseif ($problem_type == 2 || $problem_type == 3) {
            $tasker = new Tasker();

            $ctrls = [];
            $cards_to_delete = [];

            foreach ($cards as &$card) {
                $cards_to_delete[] = $card->wiegand;

                $card->person_id = 0;
                $card->save();
            }
            unset($card);

            $tasker->delCards($cards_to_delete);

            foreach ($person->divisions as $div) {
                $org = $div->organization;
                $ctrls = array_merge($ctrls, $org->controllers()->get()->toArray());
            }

            foreach ($ctrls as $ctrl) {
                $tasker->add($ctrl->id);
            }

            $tasker->send();

            $response .= ' ' . __('ac/common.and') . ' ' . __('ac/common.card_deleted');

            $description .= 'lost/broke card';
        } else {
            return null;
        }

        App\Userevent::create([
            'user_id' => Auth::id(),
            'type' => $problem_type,
            'description' => $description,
            'time' => Carbon::now()->toDateTimeString()
        ]);

        return $response;
    }
}

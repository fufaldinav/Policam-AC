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

class ControllersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function getList(Request $request)
    {
        $controllers = $request->user()->controllers;

        return response()->json($controllers);
    }

    /**
     * Устанавливает параметры открытия двери
     *
     * @param Request $request
     * @param int $ctrl_id
     * @param int $open
     * @param int $open_control
     * @param int $close_control
     *
     * @return string
     */
    public function setDoorParams(Request $request, int $ctrl_id, int $open, int $open_control = 30, int $close_control = 30): string
    {
        abort_if(!$request->user()->isAdmin(), 403);

        $tasker = new Tasker();

        $tasker->setDoorParams($open, $open_control, $close_control);
        $tasker->add($ctrl_id);

        $count = $tasker->send();

        if ($count > 0) {
            return __('Заданий успешно отправлено: :count', ['count' => $count]);
        } else {
            return __('Нет отправленных заданий');
        }
    }

    /**
     * Загружает в контроллер все карты
     *
     * @param Request $request
     * @param int $ctrl_id
     *
     * @return string
     */
    public function reloadCards(Request $request, int $ctrl_id): string
    {
        abort_if(!$request->user()->isAdmin(), 403);

        $ctrl = App\Controller::find($ctrl_id);

        $org = $ctrl->organization;

        $cards = [];

        foreach ($org->persons as $person) {
            $rc = $person->referralCode;
            if (isset($rc)) {
                if ($rc->activated === 1) {
                    $card = App\Card::firstOrCreate(['wiegand' => $rc->card]);
                    $person->cards()->save($card);
                    $cards[] = $card;
                }
            }
        }

        $tasker = new Tasker();

        for ($i = 0, $codes = [], $card_count = count($cards); $i < $card_count; $i++) {
            $codes[] = $cards[$i]->wiegand;

            /*
            | 1. Запишем задания если: а) это не первый проход
            |                          и
            |                          б) это десятый проход
            |                          или
            |                          в) это последний проход
            | 2. Очистим список кодов карт на отправку
            |
            | Таким образом сформируем задания на отправку по 10 за раз
            */
            if (($i > 0 && ($i % 10 === 0)) || ($i === ($card_count - 1))) {
                $tasker->addCards($ctrl->type, $codes);
                $tasker->add($ctrl_id);

                $codes = [];
            }
        }

        $count = $tasker->send();

        return __('Заданий успешно отправлено: :count', ['count' => $count]);
    }

    /**
     * Очищает память контроллера
     *
     * @param Request $request
     * @param int $ctrl_id
     * @param int $device
     *
     * @return string
     */
    public function clear(Request $request, int $ctrl_id, int $device = 0): string
    {
        abort_if(!$request->user()->isAdmin(), 403);

        $ctrl = App\Controller::find($ctrl_id);

        $tasker = new Tasker();

        $tasker->clearCards($ctrl->type, [$device]);
        $tasker->add($ctrl_id);

        $count = $tasker->send();

        if ($count > 0) {
            return __('Заданий успешно отправлено: :count', ['count' => $count]);
        } else {
            return __('Нет отправленных заданий');
        }
    }
}

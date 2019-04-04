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
     * @param int|null $ctrl_id
     * @param int|null $open_time
     *
     * @return string
     */
    public function setDoorParams(int $ctrl_id = null, int $open_time = null): string
    {
        if (is_null($ctrl_id) || is_null($open_time)) {
            return 'Не выбран контроллер или не задано время открытия'; //TODO перевод
        }

        $tasker = new Tasker();

        $tasker->setDoorParams($open_time);
        $tasker->add($ctrl_id);

        $count = $tasker->send();

        if ($count > 0) {
            return "Заданий успешно отправлено: $count"; //TODO перевод
        } else {
            return "Нет отправленных заданий"; //TODO перевод
        }
    }

    /**
     * Очищает память контроллера
     *
     * @param int|null $ctrl_id
     *
     * @return string
     */
    public function clear(int $ctrl_id = null): string
    {
        if (is_null($ctrl_id)) {
            return 'Не выбран контроллер'; //TODO перевод
        }

        $tasker = new Tasker();

        $tasker->clearCards();
        $tasker->add($ctrl_id);

        $count = $tasker->send();

        if ($count > 0) {
            return "Заданий успешно отправлено: $count"; //TODO перевод
        } else {
            return "Нет отправленных заданий"; //TODO перевод
        }
    }

    /**
     * Загружает в контроллер все карты
     *
     * @param int|null $ctrl_id
     *
     * @return string
     */
    public function reloadCards(int $ctrl_id = null): string
    {
        if (is_null($ctrl_id)) {
            return 'Не выбран контроллер'; //TODO перевод
        }

        $ctrl = App\Controller::find($ctrl_id);

        $org = $ctrl->organization;

        $cards = [];

        foreach ($org->divisions as $div) {
            foreach ($div->persons as $person) {
                $cards = array_merge($cards, $person->cards);
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
                $tasker->addCards($codes);
                $tasker->add($ctrl_id);

                $codes = [];
            }
        }

        return "Отправлено заданий: {$tasker->send()}"; //TODO перевод
    }
}

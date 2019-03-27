<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;

class ControllersController extends Controller
{
    /**
     * Устанавливает параметры открытия двери
     *
     * @param int|null $ctrl_id
     * @param int|null $open_time
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function setDoorParams(int $ctrl_id = null, int $open_time = null)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        if (is_null($ctrl_id) || is_null($open_time)) {
            return 'Не выбран контроллер или не задано время открытия'; //TODO перевод
        }

//        $this->load->library('task');
//
//        $this->task->setDoorParams($open_time);
//        $this->task->add($ctrl_id);
//
//        $count = $this->task->send();

//        if ($count > 0) {
//            return "Заданий успешно отправлено: $count"; //TODO перевод
//        } else {
//            return "Нет отправленных заданий"; //TODO перевод
//        }
    }

    /**
     * Очищает память контроллера
     *
     * @param int|null $ctrl_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function clear(int $ctrl_id = null)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        if (is_null($ctrl_id)) {
            return 'Не выбран контроллер'; //TODO перевод
        }

//        $this->load->library('task');
//
//        $this->task->clearCards();
//        $this->task->add($ctrl_id);
//
//        $count = $this->task->send();

//        if ($count > 0) {
//            echo "Заданий успешно отправлено: $count"; //TODO перевод
//        } else {
//            echo "Нет отправленных заданий"; //TODO перевод
//        }
    }

    /**
     * Загружает в контроллер все карты
     *
     * @param int|null $ctrl_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function reload_cards(int $ctrl_id = null)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        if (is_null($ctrl_id)) {
            return 'Не выбран контроллер'; //TODO перевод
        }


//        $this->load->library('task');

        $ctrl = App\Controller::find($ctrl_id);

        $org = $ctrl->organization;

        $cards = [];

        foreach ($org->divisions as $div) {
            foreach ($div->persons as $person) {
                $cards = array_merge($cards, $person->cards);
            }
        }

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
//                $this->task->addCards($codes);
//                $this->task->add($ctrl_id);

                $codes = [];
            }
        }

//        return "Отправлено заданий: {$this->task->send()}"; //TODO перевод
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Controllers
 *
 * @property Card_model $card
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 * @property Task_mode $task
 */
class Controllers extends CI_Controller
{
    /**
     * @var int
     */
    private $_user_id;

    /**
     * @var mixed[] $orgs
     */
    private $orgs;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->is_admin()) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->_user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * Устанавливает время параметры открытия
     *
     * @param int|null $ctrl_id   ID контроллера
     * @param int|null $open_time Время открытия в 0.1 сек
     *
     * @return void
     */
    public function set_door_params(int $ctrl_id = null, int $open_time = null): void
    {
        if (isset($ctrl_id) && isset($open_time)) {
            $this->ac->load('task');

            $this->task->controller_id = $ctrl_id;
            $this->task->set_door_params($open_time);
            $count = $this->task->save();
            if ($count > 0) {
                echo "Заданий успешно отправлено: $count"; //TODO перевод
            }
        } else {
            echo 'Не выбран контроллер или не задано время открытия'; //TODO перевод
        }
    }

    /**
     * Удаляет все карты из контроллера
     *
     * @param int|null $ctrl_id ID контроллера
     *
     * @return void
     */
    public function clear(int $ctrl_id = null): void
    {
        if (isset($ctrl_id)) {
            $this->ac->load('task');

            $this->task->controller_id = $ctrl_id;
            $this->task->clear_cards();
            $count = $this->task->save();
            if ($count > 0) {
                echo "Заданий успешно отправлено: $count"; //TODO перевод
            }
        } else {
            echo 'Не выбран контроллер'; //TODO перевод
        }
    }

    /**
     * Выгружает все карты в контроллер
     *
     * @param int|null $ctrl_id ID контроллера
     *
     * @return void
     */
    public function reload_cards(int $ctrl_id = null): void
    {
        $this->ac->load('org');

        $this->org->get_list($this->_user_id); //TODO

        if (! isset($ctrl_id)) {
            echo 'Не выбран контроллер'; //TODO перевод
        } elseif (is_null($this->org->first())) {
            echo 'Нет организаций'; //TODO перевод
        } else {
            $this->ac->load('div');
            $this->ac->load('task');

            $this->task->controller_id = $ctrl_id;

            $cards = [];

            $divs = $this->div->get_list($this->org->first('id'));

            foreach ($divs as $div) {
                $this->ac->load('person');

                $div->persons = $this->person->get_list($div->id);

                foreach ($div->persons as $person) {
                    $this->ac->load('card');

                    $person->cards = $this->card->get_list($person->id);

                    $cards = array_merge($cards, $person->cards);
                }
            }

            $card_count = count($cards);
            $counter = 0;
            $codes = [];
            for ($i = 0; $i < $card_count; $i++) {
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
                    $this->task->add_cards($codes);
                    $counter += $this->task->save();

                    $codes = [];
                }
            }

            echo "Отправлено заданий: $counter"; //TODO перевод
        }
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Controllers
 *
 * @property Task $task
 */
class Controllers extends CI_Controller
{
    /**
     * Текущий пользователь
     *
     * @var int
     */
    private $_user;

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

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->_user = new \ORM\Users($user_id);
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
        if (! isset($ctrl_id) || ! isset($open_time)) {
            echo 'Не выбран контроллер или не задано время открытия'; //TODO перевод
            exit;
        }

        $this->load->library('task');

        $this->task->set_door_params($open_time);
        $this->task->add($ctrl_id);

        $count = $this->task->send();

        if ($count > 0) {
            echo "Заданий успешно отправлено: $count"; //TODO перевод
        } else {
            echo "Нет отправленных заданий"; //TODO перевод
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
        if (! isset($ctrl_id)) {
            echo 'Не выбран контроллер'; //TODO перевод
            exit;
        }

        $this->load->library('task');

        $this->task->clear_cards();
        $this->task->add($ctrl_id);

        $count = $this->task->send();

        if ($count > 0) {
            echo "Заданий успешно отправлено: $count"; //TODO перевод
        } else {
            echo "Нет отправленных заданий"; //TODO перевод
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
        if (! isset($ctrl_id)) {
            echo 'Не выбран контроллер'; //TODO перевод
            exit;
        }

        $this->ac->load([
          'Cards',
          'Controllers',
          'Divisions',
          'Organizations',
          'Persons']);

        $this->load->library('task');

        $ctrl = new \ORM\Controllers($ctrl_id);

        $org = $ctrl->organization;

        $cards = [];

        $divs = $org->divisions;

        foreach ($divs as $div) {
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
                $this->task->add_cards($codes);
                $this->task->add($ctrl_id);

                $codes = [];
            }
        }

        echo "Отправлено заданий: {$this->task->send()}"; //TODO перевод
    }
}

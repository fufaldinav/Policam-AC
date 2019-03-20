<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Cards
 *
 * @property Task $task
 */
class Cards extends CI_Controller
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

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->_user = new \Orm\Users($user_id);
    }

    /**
     * Закрепляет карту за человеком
     *
     * @param int|null $card_id   ID карты
     * @param int      $person_id ID человека
     *
     * @return void
     */
    public function holder(int $card_id = null, int $person_id = 0): void
    {
        if (! isset($card_id)) {
            echo 0;
            exit;
        }

        $this->ac->load('Cards');
        $this->ac->load('Controllers');
        $this->ac->load('Organizations');

        $this->load->library('task');

        $card = new \Orm\Cards($card_id);

        $card->person_id = $person_id;

        $org = $this->_user->first('organizations');

        $ctrls = $org->controllers;

        if ($card->person_id == 0) {
            $this->task->del_cards([$card->wiegand]);
        } else {
            $this->task->add_cards([$card->wiegand]);
        }

        foreach ($ctrls as $ctrl) {
            $this->task->add($ctrl->id);
            $this->task->send();
        }

        echo $card->save();
    }

    /**
     * Удаляет карту
     *
     * @param int|null $card_id ID карты
     *
     * @return void
     */
    public function delete(int $card_id = null): void
    {
        if (! isset($card_id)) {
            echo 0;
            exit;
        }

        $this->ac->load('Cards');

        $card = new \Orm\Cards($card_id);

        echo $card->remove();
    }

    /**
     * Получает список карт
     *
     * @param int|null $person_id ID человека
     *
     * @return void
     */
    public function get_list(int $person_id = null): void
    {
        $person_id = $person_id ?? 0;

        $this->ac->load('Cards');
        $this->ac->load('Persons');

        $person = new \Orm\Persons($person_id);

        header('Content-Type: application/json');

        echo json_encode($person->cards);
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Cards
 *
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Org_model $org
 * @property Task_model $org
 */
class Cards extends CI_Controller
{
    /**
     * @var int
     */
    private $_user_id;

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

        $this->_user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * Закрепляет карту за человеком
     *
     * @param int $card_id   ID карты
     * @param int $person_id ID человека
     *
     * @return void
     */
    public function holder(int $card_id, int $person_id = 0): void
    {
        $this->ac->load('card');
        $this->ac->load('ctrl');
        $this->ac->load('org');
        $this->ac->load('task');

        $this->card->get($card_id);

        $this->card->person_id = $person_id;

        $this->org->get_list($this->_user_id);

        $ctrls = $this->ctrl->get_list($this->org->first('id'));

        if ($this->card->person_id === 0) {
            foreach ($ctrls as $ctrl) {
                $this->task->controller_id = $ctrl->id;
                $this->task->del_cards([$this->card->wiegand]);
                $this->task->save();
            }
        } else {
            foreach ($ctrls as $ctrl) {
                $this->task->controller_id = $ctrl->id;
                $this->task->add_cards([$this->card->wiegand]);
                $this->task->save();
            }
        }

        echo $this->card->save();
    }

    /**
     * Удаляет карту
     *
     * @param int $card_id ID карты
     *
     * @return void
     */
    public function delete($card_id): void
    {
        $this->ac->load('card');

        echo $this->card->delete($card_id);
    }

    /**
     * Получает все неизвестные карты
     *
     * @return void
     */
    public function get_list(): void
    {
        $this->ac->load('card');

        header('Content-Type: application/json');

        echo json_encode(
            $this->card->get_list(0)
        );
    }

    /**
     * Получает карты конкретного человека
     *
     * @param int $person_id ID человека
     *
     * @return void
     */
    public function get_by_person(int $person_id): void
    {
        $this->ac->load('card');

        header('Content-Type: application/json');

        echo json_encode(
            $this->card->get_list($person_id)
        );
    }
}

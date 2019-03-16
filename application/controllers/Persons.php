<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Persons
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 * @property Photo_model $photo
 * @property Task_model $task
 */
class Persons extends CI_Controller
{
    /**
     * @var int $user_id
     */
    private $user_id;

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

        $this->load->model('ac/card_model', 'card');
        $this->load->model('ac/ctrl_model', 'ctrl');
        $this->load->model('ac/div_model', 'div');
        $this->load->model('ac/org_model', 'org');
        $this->load->model('ac/person_model', 'person');
        $this->load->model('ac/photo_model', 'photo');
        $this->load->model('ac/task_model', 'task');

        $this->user_id = $this->ion_auth->user()->row()->id;
        $this->org->get_list($this->user_id); //TODO
    }

    /**
     * Добавляет нового человека
     */
    public function add()
    {
        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $divs = json_decode($this->input->post('divs'));
        $person = json_decode($this->input->post('person'));

        $this->person->set($person);
        $this->person->save();

        if (count($divs) > 0) {
            foreach ($divs as $div_id) {
                $this->person->add_to_div($this->person->id, $div_id);
            }
        } else {
            $divs = $this->div->get_list_by_type($this->org->first('id'));
            $this->person->add_to_div($this->person->id, current($divs)->id);
        }

        if (isset($this->person->photo)) {
            $this->photo->set_person($this->person->photo, $this->person->id);
        }

        if (count($person->cards) > 0) {
            $ctrls = $this->ctrl->get_list($this->org->first('id'));

            foreach ($person->cards as $card_id) {
                $this->card->get($card_id);

                $this->card->person_id = $this->person->id;

                $this->card->save();

                foreach ($ctrls as $ctrl) {
                    $this->task->controller_id = $ctrl->id;
                    $this->task->add_cards([$this->card->wiegand]);
                    $this->task->save();
                }
            }
        }

        echo $this->person->id;
    }

    /**
     * Обновляет информацию о человеке
     */
    public function update()
    {
        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $person = json_decode($this->input->post('person'));

        $count = 0;

        $this->person->set($person);

        $count += $this->person->save();

        if (isset($this->person->photo)) {
            $count += $this->photo->set_person($this->person->photo, $this->person->id);
        }

        if (count($person->cards) > 0) {
            $ctrls = $this->ctrl->get_list($this->org->first('id'));

            foreach ($person->cards as $card_id) {
                $this->card->get($card_id);

                $this->card->person_id = $this->person->id;

                $count += $this->card->save();

                foreach ($ctrls as $ctrl) {
                    $this->task->controller_id = $ctrl->id;
                    $this->task->add_cards([$this->card->wiegand]);
                    $this->task->save();
                }
            }
        }

        echo $count;
    }

    /**
     * Удаляет человека
     *
     * @param int $person_id ID человека
     */
    public function delete(int $person_id)
    {
        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->card->get_list($person_id);

        if (count($this->card->get_list()) > 0) { //TODO стилизовать/избавиться от $this->card->get_list()
            $ctrls = $this->ctrl->get_list($this->org->first('id'));

            foreach ($this->card->get_list() as &$card) {
                $card->person_id = 0;

                foreach ($ctrls as $ctrl) {
                    $this->task->controller_id = $ctrl->id;
                    $this->task->del_cards([$card->wiegand]);
                    $this->task->save();
                }
            }
            unset($card);

            $this->card->save_list();
        }

        $photo = $this->photo->get_by_person($person_id);
        if (isset($photo)) {
            $this->photo->delete($photo->id);
        }

        $this->person->del_from_div($person_id);

        echo $this->person->delete($person_id);
    }

    /**
     * Получает человека
     *
     * @param int $person_id ID человека
     */
    public function get(int $person_id)
    {
        header('Content-Type: application/json');

        $this->person->get($person_id);

        echo json_encode($this->person);
    }

    /**
     * Получает человека по карте
     *
     * @param int $card_id ID карты
     */
    public function get_by_card(int $card_id)
    {
        $this->card->get($card_id);

        header('Content-Type: application/json');

        $this->person->get($this->card->person_id);

        $this->person->get_divs($this->person->id);

        foreach ($this->person->get_divs() as $div) {
            $this->div->get($div->div_id);
            $div = $this->div;
        }

        echo json_encode($this->person);
    }

    /**
     * Получает людей по подразделению
     *
     * @param int $div_id ID подразделения
     */
    public function get_list(int $div_id)
    {
        header('Content-Type: application/json');

        echo json_encode(
            $this->person->get_list($div_id)
        );
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Persons
 *
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
     * @var int
     */
    private $_user_id;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        $this->_user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * Добавление человека
     *
     * @return void
     */
    public function add(): void
    {
        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        if (! $this->ion_auth->in_group(2)) {
            redirect('/');
        }

        $this->ac->load('card');
        $this->ac->load('div');
        $this->ac->load('org');

        $this->load->helper('form');
        $this->load->helper('language');

        $this->org->get_list($this->_user_id); //TODO

        /*
         | Подразделения
         */
        $data = [
            'divs' => $this->div->get_list($this->org->first('id'))
        ];

        /*
         | Карты
         */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards"';

        $cards = $this->card->get_list(0);

        if (count($cards) === 0) {
            $data['cards'][] = lang('missing');
        } else {
            $data['cards'][] = lang('not_selected');
            foreach ($cards as $card) {
                $data['cards'][$card->id] = $card->wiegand;
            }
        }

        $header = [
            'org_name' => $this->org->first('name') ?? lang('missing'),
            'css_list' => ['ac'],
            'js_list' => ['add_person', 'events', 'main']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/add_person', $data);
        $this->load->view('ac/footer');
    }

    /**
     * Редактирование людей
     *
     * @return void
     */
    public function edit(): void
    {
        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        if (! $this->ion_auth->in_group(2)) {
            redirect('/');
        }

        $this->ac->load('card');
        $this->ac->load('div');
        $this->ac->load('org');
        $this->ac->load('person');

        $this->load->helper('form');
        $this->load->helper('language');

        $this->org->get_list($this->_user_id); //TODO

        /*
         | Подразделения
         */
        $data = [
            'divs' => $this->div->get_list($this->org->first('id'))
        ];

        foreach ($data['divs'] as &$div) {
            $div->persons = $this->person->get_list($div->id);

            foreach ($div->persons as &$person) {
                $person->cards = $this->card->get_list($person->id);
            }
            unset($person);
        }
        unset($div);

        /*
         | Карты
         */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards" disabled';

        $cards = $this->card->get_list(0);

        if (count($cards) === 0) {
            $data['cards'][] = lang('missing');
        } else {
            $data['cards'][] = lang('not_selected');
            foreach ($cards as $card) {
                $data['cards'][$card->id] = $card->wiegand;
            }
        }

        $header = [
            'org_name' => $this->org->first('name') ?? lang('missing'),
            'css_list' => ['ac', 'edit_persons'],
            'js_list' => ['main', 'events', 'edit_persons', 'tree']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/edit_persons', $data);
        $this->load->view('ac/footer');
    }

    /**
     * Сохраняет нового человека
     *
     * @return void
     */
    public function save(): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('card');
        $this->ac->load('ctrl');
        $this->ac->load('org');
        $this->ac->load('person');
        $this->ac->load('photo');
        $this->ac->load('task');

        $this->org->get_list($this->_user_id); //TODO

        $person = json_decode($this->input->post('person'));
        $cards = json_decode($this->input->post('cards'));
        $divs = json_decode($this->input->post('divs'));
        $photos = json_decode($this->input->post('photos'));

        $this->person->set($person);
        $this->person->save();

        /*
        | Подразделения
        */
        if (count($divs) > 0) {
            foreach ($divs as $div_id) {
                $this->person->add_to_div($this->person->id, $div_id);
            }
        } else {
            $this->ac->load('div');

            $divs = $this->div->get_list_by_type($this->org->first('id'));
            $this->person->add_to_div($this->person->id, current($divs)->id);
        }

        /*
        | Фотографии
        */
        foreach ($photos as $photo_id) {
            $this->photo->get($photo_id);
            $this->photo->person_id = $this->person->id;
            $this->photo->save();
        }

        /*
        | Карты
        */
        $ctrls = $this->ctrl->get_list($this->org->first('id'));

        foreach ($cards as $card_id) {
            $this->card->get($card_id);
            $this->card->person_id = $this->person->id;
            $this->card->save();

            foreach ($ctrls as $ctrl) {
                $this->task->controller_id = $ctrl->id;
                $this->task->add_cards([$this->card->wiegand]);
                $this->task->save();
            }
        }

        echo $this->person->id;
    }

    /**
     * Обновляет информацию о человеке
     *
     * @return void
     */
    public function update(): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('card');
        $this->ac->load('ctrl');
        $this->ac->load('org');
        $this->ac->load('person');
        $this->ac->load('photo');
        $this->ac->load('task');

        $this->org->get_list($this->_user_id); //TODO

        $person = json_decode($this->input->post('person'));
        $cards = json_decode($this->input->post('cards'));
        $photos = json_decode($this->input->post('photos'));

        $count = 0;

        $this->person->set($person);
        $count += $this->person->save();

        /*
        | Фотографии
        */
        foreach ($photos as $photo_id) {
            $this->photo->get($photo_id);
            $this->photo->person_id = $this->person->id;
            $count += $this->photo->save();
        }

        /*
        | Карты
        */
        $ctrls = $this->ctrl->get_list($this->org->first('id'));

        foreach ($cards as $card_id) {
            $this->card->get($card_id);
            $this->card->person_id = $this->person->id;
            $count += $this->card->save();

            foreach ($ctrls as $ctrl) {
                $this->task->controller_id = $ctrl->id;
                $this->task->add_cards([$this->card->wiegand]);
                $this->task->save();
            }
        }

        echo $count;
    }

    /**
     * Удаляет человека
     *
     * @param int $person_id ID человека
     *
     * @return void
     */
    public function delete(int $person_id): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('card');
        $this->ac->load('ctrl');
        $this->ac->load('org');
        $this->ac->load('person');
        $this->ac->load('photo');
        $this->ac->load('task');

        $this->org->get_list($this->_user_id); //TODO

        $this->card->get_list($person_id);

        /*
        | Карты
        */
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

        /*
        | Фотографии
        */
        $photos = $this->photo->get_list($person_id);

        foreach ($photos as $photo) {
            $this->photo->delete_file($photo->id);
        }

        /*
        | Подразделения
        */
        $this->person->del_from_div($person_id);

        echo $this->person->delete($person_id);
    }

    /**
     * Получает человека
     *
     * @param int $person_id ID человека
     *
     * @return void
     */
    public function get(int $person_id): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $this->ac->load('person');
        $this->ac->load('photo');

        $this->person->get($person_id);

        header('Content-Type: application/json');

        echo json_encode([
          'person' => $this->person,
          'photos' => $this->photo->get_list($person_id)
        ]);
    }

    /**
     * Получает человека по карте
     *
     * @param int $card_id ID карты
     *
     * @return void
     */
    public function get_by_card(int $card_id): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $this->ac->load('card');
        $this->ac->load('div');
        $this->ac->load('person');
        $this->ac->load('photo');

        $this->card->get($card_id);

        $this->person->get($this->card->person_id);

        $this->person->get_divs($this->person->id);

        foreach ($this->person->get_divs() as $div) {
            $this->div->get($div->div_id);
            $div = $this->div;
        }

        header('Content-Type: application/json');

        echo json_encode([
          'person' => $this->person,
          'photos' => $this->photo->get_list($this->person->id)
        ]);
    }

    /**
     * Получает людей по подразделению
     *
     * @param int $div_id ID подразделения
     *
     * @return void
     */
    public function get_list(int $div_id): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $this->ac->load('person');

        header('Content-Type: application/json');

        echo json_encode(
            $this->person->get_list($div_id)
        );
    }
}

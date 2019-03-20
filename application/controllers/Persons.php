<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Persons
 *
 * @property Task $task
 */
class Persons extends CI_Controller
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

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->_user = new \Orm\Users($user_id);
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

        $this->ac->load('Cards');
        $this->ac->load('Divisions');
        $this->ac->load('Organizations');
        $this->ac->load('Persons');

        $this->load->helper('form');
        $this->load->helper('language');

        $org = $this->_user->first('organizations');

        /*
         | Подразделения
         */
        $data = [
            'divs' => $org->divisions
        ];

        /*
         | Карты
         */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards"';

        $person = new \Orm\Persons(0);

        $cards = $person->cards;

        if (! $cards) {
            $data['cards'][] = lang('missing');
        } else {
            $data['cards'][] = lang('not_selected');
            foreach ($cards as $card) {
                $data['cards'][$card->id] = $card->wiegand;
            }
        }

        $header = [
            'org_name' => $org->name ?? lang('missing'),
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

        $this->ac->load('Cards');
        $this->ac->load('Divisions');
        $this->ac->load('Organizations');
        $this->ac->load('Persons');

        $this->load->helper('form');
        $this->load->helper('language');

        $org = $this->_user->first('organizations');

        /*
         | Подразделения
         */
        $data = [
            'divs' => $org->divisions
        ];

        // foreach ($data['divs'] as $div) {
        //     $div->persons = $this->person->get_list($div->id);
        //
        //     foreach ($div->persons as &$person) {
        //         $person->cards = $this->card->get_list($person->id);
        //     }
        //     unset($person);
        // }

        /*
         | Карты
         */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards" disabled';

        $person = new \Orm\Persons(0);

        $cards = $person->cards;

        if (! $cards) {
            $data['cards'][] = lang('missing');
        } else {
            $data['cards'][] = lang('not_selected');
            foreach ($cards as $card) {
                $data['cards'][$card->id] = $card->wiegand;
            }
        }

        $header = [
            'org_name' => $org->name ?? lang('missing'),
            'css_list' => ['ac', 'edit_persons'],
            'js_list' => ['main', 'events', 'edit_persons', 'tree']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/edit_persons', $data);
        $this->load->view('ac/footer');
    }

    /**
     * Сохраняет человека
     *
     * @param int|null $person_id ID человека
     *
     * @return void
     */
    public function save(int $person_id = null): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('Cards');
        $this->ac->load('Divisions');
        $this->ac->load('Controllers');
        $this->ac->load('Organizations');
        $this->ac->load('Persons');
        $this->ac->load('Photos');

        $this->load->library('task');

        $org = $this->_user->first('organizations');

        $person_data = json_decode($this->input->post('person'));
        $card_list = json_decode($this->input->post('cards'));
        $div_list = json_decode($this->input->post('divs'));
        $photo_list = json_decode($this->input->post('photos'));

        $person = new \Orm\Persons($person_id);

        $person->set($person_data);
        $person->save();

        /*
        | Подразделения
        */
        if ($div_list) {
            foreach ($div_list as $div_id) {
                $div = new \Orm\Divisions($div_id);

                $person->bind($div);
            }
        } else {
            $div = new \Orm\Divisions([
              'org_id' => $org->id,
              'type' => 0
            ]);

            $person->bind($div);
        }

        /*
        | Фотографии
        */
        foreach ($photo_list as $photo_id) {
            $photo = new \Orm\Photos($photo_id);

            $photo->person_id = $person->id;
            $photo->save();
        }

        /*
        | Карты
        */
        $ctrls = $org->controllers;

        foreach ($card_list as $card_id) {
            $card = new \Orm\Cards($card_id);

            $card->person_id = $person->id;
            $card->save();

            foreach ($ctrls as $ctrl) {
                $this->task->add_cards([$this->card->wiegand]);
                $this->task->add($ctrl->id);
                $this->task->send();
            }
        }

        echo $person->id;
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

        $this->ac->load('Cards');
        $this->ac->load('Divisions');
        $this->ac->load('Controllers');
        $this->ac->load('Organizations');
        $this->ac->load('Persons');
        $this->ac->load('Photos');

        $this->load->library('task');

        $org = $this->_user->first('organizations');

        $person = new \Orm\Persons($person_id);

        /*
        | Подразделения
        */
        $divs = $person->divisions;

        foreach ($divs as $div) {
            $person->unbind($div);
        }

        /*
        | Фотографии
        */
        $photos = $person->photos;

        foreach ($photos as $photo) {
            $photo->person_id = null; //TODO удаление файла
            $photo->save();
        }

        /*
        | Карты
        */
        $cards = $person->cards;

        $ctrls = $org->controllers;

        foreach ($cards as $card) {
            $card->person_id = 0;
            $card->save();

            foreach ($ctrls as $ctrl) {
                $this->task->del_cards([$card->wiegand]);
                $this->task->add($ctrl->id);
                $this->task->send();
            }
        }

        echo $person->remove();
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

        $this->ac->load('Persons');
        $this->ac->load('Photos');

        $person = new \Orm\Persons($person_id);

        header('Content-Type: application/json');

        echo json_encode([
          'person' => $person,
          'photos' => $person->photos
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

        $this->ac->load('Cards');
        $this->ac->load('Persons');
        $this->ac->load('Photos');

        $card = new \Orm\Cards($card_id);

        $person = $card->person;

        header('Content-Type: application/json');

        echo json_encode([
          'person' => $person,
          'photos' => $person->photos
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

        $this->ac->load('Divisions');
        $this->ac->load('Persons');

        $div = new \Orm\Divisions($div_id);

        header('Content-Type: application/json');

        echo json_encode($div->persons);
    }
}

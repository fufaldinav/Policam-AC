<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Persons
 *
 * @property Photo $photo
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
        $this->_user = new \ORM\Users($user_id);
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

        $this->ac->load([
          'Cards',
          'Divisions',
          'Organizations',
          'Persons'
        ]);

        $this->load->helper(['form', 'language']);

        $orgs = $this->_user->organizations->get();
        $org = $this->_user->organizations->first();

        /*
        | Подразделения
        */
        $divs = @$org->divisions->get();
        $data = [
            'divs' => $divs ?? []
        ];

        /*
        | Карты
        */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards"';

        $person = new \ORM\Persons(0);

        $cards = $person->cards->get();

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

        $this->ac->load([
          'Cards',
          'Divisions',
          'Organizations',
          'Persons'
        ]);

        $this->load->helper(['form', 'language']);

        $orgs = $this->_user->organizations->get();
        $org = $this->_user->organizations->first();

        /*
        | Подразделения
        */
        $divs = @$org->divisions->get();
        $data = [
            'divs' => $divs ?? []
        ];

        /*
        | Карты
        */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards" disabled';

        $person = new \ORM\Persons(0);

        $cards = $person->cards->get();

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

        $this->ac->load([
          'Cards',
          'Divisions',
          'Controllers',
          'Organizations',
          'Persons',
          'Photos'
        ]);

        $this->load->library('task');

        $orgs = $this->_user->organizations->get();
        $org = $this->_user->organizations->first();

        $person_data = json_decode($this->input->post('person'));
        $card_list = json_decode($this->input->post('cards'));
        $div_list = json_decode($this->input->post('divs'));
        $photo_list = json_decode($this->input->post('photos'));

        $person = new \ORM\Persons($person_id);

        $person->set($person_data);
        $person->save();

        /*
        | Карты
        */
        $ctrls = @$org->controllers->get();
        $ctrls = $ctrls ?? [];

        foreach ($card_list as $card_id) {
            $card = new \ORM\Cards($card_id);

            $card->person_id = $person->id;
            $card->save();

            $this->task->add_cards([$card->wiegand]);

            foreach ($ctrls as $ctrl) {
                $this->task->add($ctrl->id);
            }
        }

        $this->task->send();

        /*
        | Подразделения
        */
        if ($div_list) {
            foreach ($div_list as $div_id) {
                $div = new \ORM\Divisions($div_id);

                $person->bind($div);
            }
        } else {
            $div = new \ORM\Divisions([
                'org_id' => $org->id ?? 0,
                'type' => 0
            ]);

            $person->bind($div);
        }

        /*
        | Фотографии
        */
        foreach ($photo_list as $photo_id) {
            $photo = new \ORM\Photos($photo_id);

            $photo->person_id = $person->id;
            $photo->save();
        }

        echo $person->id;
    }

    /**
     * Удаляет человека
     *
     * @param int|null $person_id ID человека
     *
     * @return void
     */
    public function delete(int $person_id = null): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        if (! isset($person_id)) {
            echo 0;
            exit;
        }

        $this->ac->load([
          'Cards',
          'Divisions',
          'Controllers',
          'Organizations',
          'Persons',
          'Photos'
        ]);

        $this->load->library(['task', 'photo']);

        $orgs = $this->_user->organizations->get();
        $org = $this->_user->organizations->first();

        $person = new \ORM\Persons($person_id);

        /*
        | Подразделения
        */
        foreach ($person->divisions->get() as $div) {
            $person->unbind($div);
        }

        /*
        | Фотографии
        */
        foreach ($person->photos as $photo) {
            $this->photo->remove($photo->id);
        }

        /*
        | Карты
        */
        $ctrls = @$org->controllers->get();
        $ctrls = $ctrls ?? [];

        foreach ($person->cards->get() as $card) {
            $card->person_id = 0;
            $card->save();

            $this->task->del_cards([$card->wiegand]);

            foreach ($ctrls as $ctrl) {
                $this->task->add($ctrl->id);
            }
        }

        $this->task->send();

        /*
        | Подписки
        */
        $subs = $person->users->get();

        foreach ($subs as $sub) {
            $person->unbind($sub);
        }

        echo $person->remove();
    }

    /**
     * Получает человека
     *
     * @param int|null $person_id ID человека
     *
     * @return void
     */
    public function get(int $person_id = null): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! isset($person_id)) {
            exit;
        }

        $this->ac->load(['Divisions', 'Persons', 'Photos']);

        $person = new \ORM\Persons($person_id);

        header('Content-Type: application/json');

        echo json_encode([
          'person' => $person,
          'photos' => $person->photos->get(),
          'divs' => $person->divisions->get()
        ]);
    }

    /**
     * Получает человека по карте
     *
     * @param int|null $card_id ID карты
     *
     * @return void
     */
    public function get_by_card(int $card_id = null): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! isset($person_id)) {
            exit;
        }

        $this->ac->load([
          'Cards',
          'Divisions',
          'Persons',
          'Photos'
        ]);

        $card = new \ORM\Cards($card_id);

        $person = $card->person;

        header('Content-Type: application/json');

        echo json_encode([
          'person' => $person ?? [],
          'photos' => $person->photos
        ]);
    }

    /**
     * Получает людей по подразделению
     *
     * @param int|null $div_id ID подразделения
     *
     * @return void
     */
    public function get_list(int $div_id = null): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! isset($div_id)) {
            exit;
        }

        $this->ac->load(['Divisions', 'Persons']);

        $div = new \ORM\Divisions($div_id);

        header('Content-Type: application/json');

        echo json_encode($div->persons->get());
    }
}

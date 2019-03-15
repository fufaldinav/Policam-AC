<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Ac
 * @property Card_model $card
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 */
class Ac extends CI_Controller
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

        $this->lang->load('ac');

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('ac/card_model', 'card');
        $this->load->model('ac/div_model', 'div');
        $this->load->model('ac/org_model', 'org');
        $this->load->model('ac/person_model', 'person');

        $this->load->helper('language');

        $this->user_id = $this->ion_auth->user()->row()->id;
        $this->org->get_list($this->user_id); //TODO
    }

    /**
     * Главная
     */
    public function index()
    {
        if ($this->ion_auth->is_admin()) {
            redirect('auth');
        } else {
            redirect('ac/observ');
        }
    }

    /**
     * Наблюдение
     */
    public function observ()
    {
        /*
         | Подразделения
         */
        $divs = $this->div->get_list($this->org->first('id'));

        $data = [
            'divs' => $divs
        ];

        $header = [
            'org_name' => $this->org->get_full_name($this->org->first('id')) ?? lang('missing'),
            'css_list' => ['ac'],
            'js_list' => ['main', 'observ']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/observation', $data);
        $this->load->view('ac/footer');
    }

    /**
     * Добавление человека
     */
    public function add_person()
    {
        if (! $this->ion_auth->in_group(2)) {
            redirect('ac/observ');
        }

        $this->load->helper('form');

        /*
         | Подразделения
         */
        $data = [
            'divs_list' => [],
            'divs_attr' => 'id="div"'
        ];

        $divs = $this->div->get_list($this->org->first('id'));

        $data['divs'] = $divs;

        if (count($divs) === 0) {
            $data['divs_list'][] = lang('missing');
        } else {
            foreach ($divs as $div) {
                $data['divs_list'][$div->id] = $div->name;
            }
        }

        /*
         | Карты
         */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards"';

        $cards = $this->card->get_list(0);
        ;

        if (count($cards) === 0) {
            $data['cards'][] = lang('missing');
        } else {
            $data['cards'][] = lang('not_selected');
            foreach ($cards as $card) {
                $data['cards'][$card->id] = $card->wiegand;
            }
        }

        $header = [
            'org_name' => $this->org->get_full_name($this->org->first('id')) ?? lang('missing'),
            'css_list' => ['ac'],
            'js_list' => ['add_person', 'events', 'main']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/add_person', $data);
        $this->load->view('ac/footer');
    }

    /**
     * Редактирование людей
     */
    public function edit_persons()
    {
        if (! $this->ion_auth->in_group(2)) {
            redirect('ac/observ');
        }

        $this->load->helper('form');

        /*
         | Подразделения
         */
        $data = [
            'divs_list' => [],
            'divs_attr' => 'id="div" disabled'
        ];

        $divs = $this->div->get_list($this->org->first('id'));

        $data['divs'] = $divs;

        if (count($divs) === 0) {
            $data['divs_list'][] = lang('missing');
        }

        foreach ($divs as &$div) {
            $data['divs_list'][$div->id] = $div->name;
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
            'org_name' => $this->org->get_full_name($this->org->first('id')) ?? lang('missing'),
            'css_list' => ['ac', 'edit_persons'],
            'js_list' => ['main', 'events', 'edit_persons', 'tree']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/edit_persons', $data);
        $this->load->view('ac/footer');
    }

    /**
     * Управление классами
     */
    public function classes()
    {
        if (! $this->ion_auth->in_group(2)) {
            redirect('ac/observ');
        }

        $this->load->library('table');

        $data = [
            'org_id' => $this->org->first('id'),
            'divs' => $this->div->get_list($this->org->first('id'))
        ];

        $header = [
            'org_name' => $this->org->get_full_name($this->org->first('id')) ?? lang('missing'),
            'css_list' => ['ac', 'tables'],
            'js_list' => ['classes']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/classes', $data);
        $this->load->view('ac/footer');
    }
}

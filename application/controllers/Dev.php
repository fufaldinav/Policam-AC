<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Dev
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Div_model $div
 * @property Notification_model $notification
 * @property Org_model $org
 * @property Person_model $person
 * @property Photo_model $photo
 * @property Server_model $server
 * @property Task_model $task
 * @property Token_model $token
 * @property Util_model $util
 */
class Dev extends CI_Controller
{
    /**
     * @var int $user_id
     */
    private $user_id;

    /**
     * @var array $orgs
     */
    private $orgs = [];

    /**
     * @var array $divs
     */
    private $divs = [];

    /**
     * @var array $persons
     */
    private $persons = [];

    /**
     * @var array $cards
     */
    private $cards = [];

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('ac');

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('ac/card_model', 'card');
        $this->load->model('ac/ctrl_model', 'ctrl');
        $this->load->model('ac/div_model', 'div');
        $this->load->model('ac/notification_model', 'notification');
        $this->load->model('ac/org_model', 'org');
        $this->load->model('ac/person_model', 'person');
        $this->load->model('ac/photo_model', 'photo');
        $this->load->model('ac/server_model', 'server');
        $this->load->model('ac/taskmodel', 'task');
        $this->load->model('ac/token_model', 'token');
        $this->load->model('ac/util_model', 'util');

        $this->load->helper('language');

        $this->user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * Главная
     */
    public function index()
    {
        $this->orgs = $this->org->get_list($this->user_id);

        foreach ($this->orgs as $org) {
            $this->divs = array_merge($this->divs, $this->div->get_list($org->id));
        }

        foreach ($this->divs as $div) {
            $this->persons = array_merge($this->persons, $this->person->get_list($div->id));
        }

        foreach ($this->persons as $person) {
            $this->cards = array_merge($this->cards, $this->card->get_list($person->id));
        }

        echo 'орг: ' . count($this->orgs) . ' подр: ' . count($this->divs) . ' люди: ' . count($this->persons) . ' ключи: ' . count($this->cards) . '<br />';

        $person = $this->person->get(1982);

        //var_dump($person);

        $divs = $this->person->get_divs($person->id);

        foreach ($divs as $div) {
            $this->div->get($div->div_id);
            $this->org->get($this->div->org_id);

            $users = $this->org->get_users($this->org->id);

            foreach ($users as $user) {
                if ($user->user_id === $this->user_id) {
                    echo "TRUE <br />";
                } else {
                    echo "FALSE <br />";
                }
            }
            if (in_array($this->user_id, $users)) {
                echo "TRUE";
            }
        }
    }
}

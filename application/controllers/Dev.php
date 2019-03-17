<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Dev
 *
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Div_model $div
 * @property Event_model $event
 * @property Org_model $org
 * @property Person_model $person
 * @property Photo_model $photo
 * @property Task_model $task
 * @property Token_model $token
 * @property Users_events_model $users_events
 */
class Dev extends CI_Controller
{
    /**
     * @var int
     */
    private $_user_id;

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

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        if (! $this->ion_auth->is_admin()) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('card');
        $this->ac->load('ctrl');
        $this->ac->load('div');
        $this->ac->load('event');
        $this->ac->load('org');
        $this->ac->load('person');
        $this->ac->load('photo');
        $this->ac->load('task');
        $this->ac->load('token');
        $this->ac->load('users_events');

        $this->load->helper('language');

        $this->_user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * Главная
     *
     * @return void
     */
    public function index(): void
    {
        $this->orgs = $this->org->get_list($this->_user_id);

        foreach ($this->orgs as $org) {
            $this->divs = array_merge(
                $this->divs,
                $this->div->get_list($org->id)
            );
        }

        foreach ($this->divs as $div) {
            $this->persons = array_merge(
                $this->persons,
                $this->person->get_list($div->id)
            );
        }

        foreach ($this->persons as $person) {
            $this->cards = array_merge(
                $this->cards,
                $this->card->get_list($person->id)
            );
        }

        echo 'орг: ' . count($this->orgs) .
             ' подр: ' . count($this->divs) .
             ' люди: ' . count($this->persons) .
             ' ключи: ' . count($this->cards) .
             '<br />';

        $this->person->get(1982);

        $divs = $this->person->get_divs($this->person->id);

        foreach ($divs as $div) {
            $this->div->get($div->div_id);
            $this->org->get($this->div->org_id);

            $users = $this->org->get_users($this->org->id);

            foreach ($users as $user) {
                if ($user->user_id === $this->_user_id) {
                    echo "TRUE <br />";
                } else {
                    echo "FALSE <br />";
                }
            }
            if (in_array($this->_user_id, $users)) {
                echo "TRUE";
            }
        }
    }

    /**
     * Тест
     *
     * @return void
     */
    public function test(): void
    {
        header('Content-Type: text/plain');
    }
}

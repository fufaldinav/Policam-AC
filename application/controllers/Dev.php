<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Dev
 */
class Dev extends CI_Controller
{
    /**
     * Текущий пользователь
     *
     * @var int
     */
    private $_user;

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

        // if (! $this->ion_auth->is_admin()) {
        //     header('HTTP/1.1 403 Forbidden');
        //     exit;
        // }

        $this->ac->load('Cards');
        $this->ac->load('Controllers');
        $this->ac->load('Divisions');
        $this->ac->load('Events');
        $this->ac->load('Organizations');
        $this->ac->load('Persons');
        $this->ac->load('Photos');
        $this->ac->load('Tasks');
        $this->ac->load('Tokens');
        $this->ac->load('Users');

        $this->load->helper('language');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->_user = new \Orm\Users($user_id);
    }

    /**
     * Главная
     *
     * @return void
     */
    public function index(): void
    {
        header('Content-Type: text/plain');

        $resp = null;

        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        var_dump($resp);
    }
}

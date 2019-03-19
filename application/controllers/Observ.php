<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Observ
 *
 * @property Div_model $div
 * @property Org_model $org
 */
class Observ extends CI_Controller
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
            redirect('auth/login');
        }

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->_user = new \Orm\Users($user_id);
    }

    /**
     * Наблюдение
     *
     * @return void
     */
    public function index(): void
    {
        $this->ac->load('Divisions');
        $this->ac->load('Organizations');

        $this->load->helper('language');

        $org = $this->_user->first('organizations');
        /*
         | Подразделения
         */
        $data = [
            'divs' => $org->divisions
        ];

        $header = [
            'org_name' => $org->name ?? lang('missing'),
            'css_list' => ['ac'],
            'js_list' => ['main', 'observ']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/observation', $data);
        $this->load->view('ac/footer');
    }
}

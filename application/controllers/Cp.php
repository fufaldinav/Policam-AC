<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Cp
 */
class Cp extends CI_Controller
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
        $this->_user = new \ORM\Users($user_id);
    }

    /**
     * Главная
     *
     * @return void
     */
    public function index(): void
    {
        $this->load->helper('language');

        $header = [
            'org_name' => lang('missing'),
            'css_list' => ['ac'],
            'js_list' => ['push']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/cp');
        $this->load->view('ac/footer');
    }
}

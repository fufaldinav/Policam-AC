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
     * @var int
     */
    private $_user_id;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->_user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * Наблюдение
     *
     * @return void
     */
    public function index(): void
    {
        $this->ac->load('div');
        $this->ac->load('org');

        $this->load->helper('language');

        $this->org->get_list($this->_user_id); //TODO
        /*
         | Подразделения
         */
        $data = [
            'divs' => $this->div->get_list($this->org->first('id'))
        ];

        $header = [
            'org_name' => $this->org->first('name') ?? lang('missing'),
            'css_list' => ['ac'],
            'js_list' => ['main', 'observ']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/observation', $data);
        $this->load->view('ac/footer');
    }
}

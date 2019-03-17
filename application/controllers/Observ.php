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
     * @var int $user_id
     */
    private $_user_id;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('ac');

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('ac/div_model', 'div');
        $this->load->model('ac/org_model', 'org');

        $this->load->helper('language');

        $this->_user_id = $this->ion_auth->user()->row()->id;
        $this->org->get_list($this->_user_id); //TODO
    }

    /**
     * Наблюдение
     *
     * @return void
     */
    public function index(): void
    {
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

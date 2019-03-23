<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Observ
 */
class Observ extends CI_Controller
{
    /** @var object Текущий пользователь */
    private $user;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->user = new \ORM\Users($user_id);
    }

    /**
     * Наблюдение
     *
     * @return void
     */
    public function index(): void
    {
        $this->ac->load(['Divisions', 'Organizations']);

        $this->load->helper('language');

        $orgs = $this->user->organizations->get();
        $org = $this->user->organizations->first();

        /*
        | Подразделения
        */
        $divs = $org->divisions
            ->order_by('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get();
        $data = [
            'divs' => $divs
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

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Cp
 * @property Card_model $card
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 */
class Cp extends CI_Controller
{
	/**
	 * @var int $user_id
	 */
	private $user_id;

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
	}

	/**
	 * Главная
	 */
	public function index()
	{
		if (! $this->ion_auth->is_admin()) {
			redirect('/');
		}

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

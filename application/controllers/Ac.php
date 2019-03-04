<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Ac
 * @property Other_model $other
 * @property Card_model $card
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 * @property Util_model $util
 */
class Ac extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('language');

		$this->load->model('ac/other_model', 'other'); //TODO
		$this->load->model('ac/card_model', 'card');
		$this->load->model('ac/div_model', 'div');
		$this->load->model('ac/org_model', 'org');
		$this->load->model('ac/person_model', 'person');
		$this->load->model('ac/util_model', 'util');

		$this->lang->load('ac');
	}

	/**
	 * Наблюдение
	 */
	public function index()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		} elseif ($this->ion_auth->is_admin()) {
			redirect('auth');
		}

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$orgs = $this->org->get_all($user_id); //TODO
		$org = array_shift($orgs); //TODO

		$header = [];
		$header['org_name'] = $this->org->get_full_name($org->id);
		$header['css_list'] = ['ac'];
		$header['js_list'] = ['main', 'observ'];

		$this->load->view('ac/header', $header);
		$this->load->view('ac/observation');
		$this->load->view('ac/footer');
	}

	/**
	 * Добавление человека
	 */
	public function add_person()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->load->helper('form');

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$orgs = $this->org->get_all($user_id); //TODO
		$org = array_shift($orgs); //TODO

		$data = [];

		/**
		 * Подразделения
		 */
		$data['divs_list'] = [];
		$data['divs_attr'] = 'id="div"';

		$divs = $this->div->get_all($org->id);

		if ($divs === null) {
			$data['divs_list']['0'] = lang('missing');
		} else {
			foreach ($divs as $div) {
				$data['divs_list'][$div->id] = $div->number . ' "' . $div->letter . '"';
			}
		}

		/**
		 * Карты
		 */
		$data['cards'] = [];
		$data['cards_attr'] = 'id="card"';

		$cards = $this->card->get_by_holder(-1);

		if ($cards === null) {
			$data['cards']['0'] = lang('missing');
		} else {
			$data['cards']['0'] = lang('not_selected');
			foreach ($cards as $row) {
				$data['cards'][$row->id] = $row->wiegand;
			}
		}

		$header = [];
		$header['org_name'] = $this->org->get_full_name($org->id);
		$header['css_list'] = ['ac'];
		$header['js_list'] = ['main', 'events', 'add_person'];

		$this->load->view('ac/header', $header);
		$this->load->view('ac/add_person', $data);
		$this->load->view('ac/footer');
	}

	/**
	 * Редактирование людей
	 */
	public function edit_persons()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->load->helper('form');

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$orgs = $this->org->get_all($user_id); //TODO
		$org = array_shift($orgs); //TODO

		$data = [];

		/**
		 * Подразделения
		 */
		$data['divs_list'] = [];
		$data['divs_attr'] = 'id="div"';

		$divs = $this->div->get_all($org->id);

		if ($divs === null) {
			$data['divs_list']['0'] = lang('missing');
		}

		foreach ($divs as &$div) {
			$data['divs_list'][$div->id] = $div->number . ' "' . $div->letter . '"';
			$div->persons = $this->person->get_all($div->id);

			foreach ($div->persons as &$person) {
	 			$person->cards = $this->card->get_by_holder($person->id);
	 		}
			unset($person);

		}
		unset($div);

		$data['divs_menu'] = $divs;

		/**
		 * Карты
		 */
		$data['cards'] = [];
		$data['cards_attr'] = 'id="card" disabled';

		$cards = $this->card->get_by_holder(-1);

		if ($cards === null) {
			$data['cards']['0'] = lang('missing');
		} else {
			$data['cards']['0'] = lang('not_selected');
			foreach ($cards as $card) {
				$data['cards'][$card->id] = $card->wiegand;
			}
		}

		$header = [];
		$header['org_name'] = $this->org->get_full_name($org->id);
		$header['css_list'] = ['ac', 'edit_persons'];
		$header['js_list'] = ['main', 'events', 'edit_persons', 'tree'];

		$this->load->view('ac/header', $header);
		$this->load->view('ac/edit_persons', $data);
		$this->load->view('ac/footer');
	}

	/**
	 * Управление классами
	 */
	public function classes()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->load->library('table');

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$orgs = $this->org->get_all($user_id); //TODO
		$org = array_shift($orgs); //TODO

		$data = [];

		$data['divs'] = $this->div->get_all($org->id);

		$header = [];
		$header['org_name'] = $this->org->get_full_name($org->id);
		$header['css_list'] = ['ac', 'tables'];
		$header['js_list'] = ['classes'];

		$this->load->view('ac/header', $header);
		$this->load->view('ac/classes', $data);
		$this->load->view('ac/footer');
	}
}

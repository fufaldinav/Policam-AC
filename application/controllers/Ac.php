<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Ac
 * @property Other_model $other
 * @property Card_model $card
 * @property Div_model $div
 * @property Org_model $org
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

		/**
		 * Подразделения
		 */
		$data['divs'] = [];

		$divs = $this->div->get_all($org->id);

		if (!$divs) {
			$data['divs']['0'] = lang('missing');
		} else {
			foreach ($divs as $div) {
				$data['divs'][$div->id] = $div->number . ' "' . $div->letter . '"';
			}
		}

		$data['divs_attr'] = 'id="div"';

		/**
		 * Карты
		 */
		$data['cards'] = [];

		$cards = $this->card->get_by_holder(-1);

		if (!$cards) {
			$data['cards']['0'] = lang('missing');
		} else {
			$data['cards']['0'] = lang('not_selected');
			foreach ($cards as $row) {
				$data['cards'][$row->id] = $row->wiegand;
			}
		}

		$data['cards_attr'] = 'id="card"';

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

		$data['menu'] = '<ul class="tree-container">';

		/**
		 * Подразделения
		 */
		$data['divs'] = [];

		$divs = $this->div->get_all($org->id);

		if ($divs === null) {
			$data['divs']['0'] = lang('missing');
		} else {
			$persons = $this->other->get_persons_and_cards_by_org($org->id);
			$last_k = count($divs) - 1;
			foreach ($divs as $k => $div) {
				$data['divs'][$div->id] = $div->number . ' "' . $div->letter . '"';
				$data['menu'] .= '<li class="tree-node tree-is-root tree-expand-closed' . (($k == $last_k) ? ' tree-is-last' : '') . '">'
											. '<div class="tree-expand"></div>'
											. '<div class="tree-content tree-expand-content">'
											. $data['divs'][$div->id]
											. '</div>'
											. '<ul class="tree-container">';
				$cur_div = $persons[$div->number.$div->letter]; //number + letter для сортировки дерева 1А -> 1Б -> 2А etc.
				$last_n = count($cur_div) - 1;
				foreach ($cur_div as $n => $p) {
					$data['menu'] .= '<li id="person' . $p->id . '" class="tree-node tree-expand-leaf' . (($n == $last_n) ? ' tree-is-last' : '') . '">'
												. '<div class="tree-expand"></div>'
												. '<div class="tree-content">'
												. (($p->card_id) ? '(+) ' : '')
												. '<a class="person" href="#' . $p->id . '" onClick="getPersonInfo(' . $p->id . ')">' . $p->f . ' ' . $p->i . '</a>'
												. '</div>'
												. '</li>';
				}
				$data['menu'] .= '</ul></li>';
			}
		}

		$data['divs_attr'] = 'id="div"';

		/**
		 * Карты
		 */
		$data['cards'] = [];

		$cards = $this->card->get_by_holder(-1);

		if (!$cards) {
			$data['cards']['0'] = lang('missing');
		} else {
			$data['cards']['0'] = lang('not_selected');
			foreach ($cards as $row) {
				$data['cards'][$row->id] = $row->wiegand;
			}
		}

		$data['cards_attr'] = 'id="card" disabled';

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

		$this->db->where('org_id', $org->id);
		$this->db->order_by('number ASC, letter ASC');
		$query = $this->db->get('divisions');

		$this->table->set_heading(lang('number'), lang('letter'), '');

		$delete = '<button onclick="saveDivision(' . $org->id . ')">' . lang('save') . '</button>';

		$this->table->add_row(
			'<input id="number" type="text" size="2" maxlength="2" required />',
			'<input id="letter" type="text" size="1" maxlength="1" required />',
			$delete
		);

		foreach ($query->result() as $row) {
			$delete = '<button onclick="deleteDivision(' . $row->id . ')">' . lang('delete') . '</button>';
			$this->table->add_row($row->number, $row->letter, $delete);
		}

		$data['table'] = $this->table->generate();

		$header['org_name'] = $this->org->get_full_name($org->id);
		$header['css_list'] = ['ac', 'tables'];
		$header['js_list'] = ['classes'];

		$this->load->view('ac/header', $header);
		$this->load->view('ac/classes', $data);
		$this->load->view('ac/footer');
	}
}

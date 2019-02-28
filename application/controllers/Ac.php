<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ac extends CI_Controller
{
	private $user_id;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('language');

		$this->load->model('ac_model');
		$this->lang->load('ac');

		if ($this->ion_auth->logged_in()) {
			$this->user_id = $this->ion_auth->user()->row()->id;
		}
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		} elseif ($this->ion_auth->is_admin()) {
			redirect('auth');
		}

		$org_id = $this->ac_model->get_org_by_user($this->user_id)->id;

		$header['org_name'] = $this->ac_model->render_org_name($org_id);
		$header['css'] = $this->ac_model->render_css(['ac']);
		$header['js'] = $this->ac_model->render_js(['main', 'observ']);
		$header['nav'] = $this->ac_model->render_nav();

		$this->load->view('ac/header', $header);
		$this->load->view('ac/observation');
		$this->load->view('ac/footer');
	}

	public function add_person()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->load->helper('form');

		$org_id = $this->ac_model->get_org_by_user($this->user_id)->id;

		//классы
		$data['divisions'] = [];

		$divisions = $this->ac_model->get_divisions_by_org($org_id);

		if (!$divisions) {
			$data['divisions']['0'] = lang('missing');
		} else {
			foreach ($divisions as $div) {
				$data['divisions'][$div->id] = $div->number . ' "' . $div->letter . '"';
			}
		}

		$data['div_attr'] = 'id="div"';

		//карты
		$data['cards'] = [];

		$cards = $this->ac_model->get_cards();

		if (!$cards) {
			$data['cards']['0'] = lang('missing');
		} else {
			$data['cards']['0'] = lang('not_selected');
			foreach ($cards as $row) {
				$data['cards'][$row->id] = $row->wiegand;
			}
		}

		$data['card_attr'] = 'id="card"';

		$header['org_name'] = $this->ac_model->render_org_name($org_id);
		$header['css'] = $this->ac_model->render_css(['ac']);
		$header['js'] = $this->ac_model->render_js(['main', 'events', 'add_person']);
		$header['nav'] = $this->ac_model->render_nav();

		$this->load->view('ac/header', $header);
		$this->load->view('ac/add_person', $data);
		$this->load->view('ac/footer');
	}

	public function edit_persons()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->load->helper('form');

		$org_id = $this->ac_model->get_org_by_user($this->user_id)->id;

		$data['menu'] = '<ul class="tree-container">';

		//классы
		$data['divisions'] = [];

		$divisions = $this->ac_model->get_divisions_by_org($org_id);

		if (!$divisions) {
			$data['divisions']['0'] = lang('missing');
		} else {
			$persons = $this->ac_model->get_persons_and_cards_by_org($org_id);
			$last_k = count($divisions) - 1;
			foreach ($divisions as $k => $div) {
				$data['divisions'][$div->id] = $div->number . ' "' . $div->letter . '"';
				$data['menu'] .= '<li class="tree-node tree-is-root tree-expand-closed' . (($k == $last_k) ? ' tree-is-last' : '') . '">'
											. '<div class="tree-expand"></div>'
											. '<div class="tree-content tree-expand-content">'
											. $data['divisions'][$div->id]
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

		$data['div_attr'] = 'id="div"';

		//классы
		$data['cards'] = [];

		$cards = $this->ac_model->get_cards();

		if (!$cards) {
			$data['cards']['0'] = lang('missing');
		} else {
			$data['cards']['0'] = lang('not_selected');
			foreach ($cards as $row) {
				$data['cards'][$row->id] = $row->wiegand;
			}
		}

		$data['card_attr'] = 'id="card" disabled';

		$header['org_name'] = $this->ac_model->render_org_name($org_id);
		$header['css'] = $this->ac_model->render_css(['ac', 'edit_persons']);
		$header['js'] = $this->ac_model->render_js(['main', 'events', 'edit_persons', 'tree']);
		$header['nav'] = $this->ac_model->render_nav();

		$this->load->view('ac/header', $header);
		$this->load->view('ac/edit_persons', $data);
		$this->load->view('ac/footer');
	}

	public function classes()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->load->library('table');

		$org_id = $this->ac_model->get_org_by_user($this->user_id)->org_id;

		$this->db->where('org_id', $org_id);
		$this->db->order_by('number ASC, letter ASC');
		$query = $this->db->get('divisions');

		$this->table->set_heading(lang('number'), lang('letter'), '');

		$delete = '<button onclick="saveDivision(' . $org_id . ')">' . lang('save') . '</button>';

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

		$header['org_name'] = $this->ac_model->render_org_name($org_id);
		$header['css'] = $this->ac_model->render_css(['ac', 'tables']);
		$header['js'] = $this->ac_model->render_js(['classes']);
		$header['nav'] = $this->ac_model->render_nav();

		$this->load->view('ac/header', $header);
		$this->load->view('ac/classes', $data);
		$this->load->view('ac/footer');
	}
}

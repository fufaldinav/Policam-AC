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

		$school_id = $this->ac_model->get_school_by_user($this->user_id)->id;

		$header['school'] = $this->ac_model->render_school_name($school_id);
		$header['css'] = $this->ac_model->render_css(['ac']);
		$header['js'] = $this->ac_model->render_js(['main', 'ac']);
		$header['nav'] = $this->ac_model->render_nav();

		$this->load->view('ac/header', $header);
		$this->load->view('ac/home');
		$this->load->view('ac/footer');
	}

	public function add_pers()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->load->helper('form');

		$school_id = $this->ac_model->get_school_by_user($this->user_id)->id;

		//классы
		$data['classes'] = [];

		$classes = $this->ac_model->get_classes_by_school($school_id);

		if (!$classes) {
			$data['classes']['0'] = lang('missing');
		} else {
			foreach ($classes as $row) {
				$data['classes'][$row->id] = $row->number . ' "' . $row->letter . '"';
			}
		}

		$data['class_attr'] = 'id="class"';

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

		$header['school'] = $this->ac_model->render_school_name($school_id);
		$header['css'] = $this->ac_model->render_css(['ac']);
		$header['js'] = $this->ac_model->render_js(['main', 'events', 'add_pers']);
		$header['nav'] = $this->ac_model->render_nav();

		$this->load->view('ac/header', $header);
		$this->load->view('ac/add_personal', $data);
		$this->load->view('ac/footer');
	}

	public function edit_pers()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->load->helper('form');

		$school_id = $this->ac_model->get_school_by_user($this->user_id)->id;

		$data['menu'] = '<ul class="tree-container">';

		//классы
		$data['classes'] = [];

		$classes = $this->ac_model->get_classes_by_school($school_id);

		if (!$classes) {
			$data['classes']['0'] = lang('missing');
		} else {
			$personal = $this->ac_model->get_pers_and_cards_by_school($school_id);
			$last_k = count($classes) - 1;
			foreach ($classes as $k => $row) {
				$data['classes'][$row->id] = $row->number . ' "' . $row->letter . '"';
				$data['menu'] .= '<li class="tree-node tree-is-root tree-expand-closed' . (($k == $last_k) ? ' tree-is-last' : '') . '">'
											. '<div class="tree-expand"></div>'
											. '<div class="tree-content tree-expand-content">'
											. $data['classes'][$row->id]
											. '</div>'
											. '<ul class="tree-container">';
				$cur_class = $personal[$row->number.$row->letter]; //number + letter для сортировки дерева 1А -> 1Б -> 2А etc.
				$last_n = count($cur_class) - 1;
				foreach ($cur_class as $n => $p) {
					$data['menu'] .= '<li id="pers' . $p->id . '" class="tree-node tree-expand-leaf' . (($n == $last_n) ? ' tree-is-last' : '') . '">'
												. '<div class="tree-expand"></div>'
												. '<div class="tree-content">'
												. (($p->card_id) ? '(+) ' : '')
												. '<a class="pers" href="#' . $p->id . '" onClick="getPersData(' . $p->id . ')">' . $p->f . ' ' . $p->i . '</a>'
												. '</div>'
												. '</li>';
				}
				$data['menu'] .= '</ul></li>';
			}
		}

		$data['class_attr'] = 'id="class"';

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

		$header['school'] = $this->ac_model->render_school_name($school_id);
		$header['css'] = $this->ac_model->render_css(['ac', 'edit_pers']);
		$header['js'] = $this->ac_model->render_js(['main', 'events', 'edit_pers', 'tree']);
		$header['nav'] = $this->ac_model->render_nav();

		$this->load->view('ac/header', $header);
		$this->load->view('ac/edit_personal', $data);
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

		$school_id = $this->ac_model->get_school_by_user($this->user_id)->school_id;

		$this->db->where('school_id', $school_id);
		$this->db->order_by('number ASC, letter ASC');
		$query = $this->db->get('classes');

		$this->table->set_heading(lang('number'), lang('letter'), '');

		$delete = '<button onclick="save(' . $school_id . ')">' . lang('save') . '</button>';

		$this->table->add_row(
			'<input id="number" type="text" size="2" maxlength="2" required />',
			'<input id="letter" type="text" size="1" maxlength="1" required />',
			$delete
		);

		foreach ($query->result() as $row) {
			$delete = '<button onclick="del(' . $row->id . ')">' . lang('delete') . '</button>';
			$this->table->add_row($row->number, $row->letter, $delete);
		}

		$data['table'] = $this->table->generate();

		$header['school'] = $this->ac_model->render_school_name($school_id);
		$header['css'] = $this->ac_model->render_css(['ac', 'tables']);
		$header['js'] = $this->ac_model->render_js(['classes']);
		$header['nav'] = $this->ac_model->render_nav();

		$this->load->view('ac/header', $header);
		$this->load->view('ac/classes', $data);
		$this->load->view('ac/footer');
	}
}

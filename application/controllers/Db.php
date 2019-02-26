<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Db extends CI_Controller {

	private $user_id;

	public function __construct()	{
		parent::__construct();

		$this->load->helper('language');

		$this->load->model('ac_model');
		$this->lang->load('ac');

		if ($this->ion_auth->logged_in()) {
			$this->user_id = $this->ion_auth->user()->row()->id;
		}
	}

	public function index() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
	}

	public function save_pers() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$json_data = json_decode($this->input->post('data'), TRUE);

		if (isset($json_data['photo'])) {
			$this->db->select('id');
			$this->db->where('hash', $json_data['photo']);
			$query = $this->db->get('photo');
			$photo_id = $query->row()->id;
		}


		$data = [
					'class_id' => $json_data['class'],
					'f' => $json_data['f'],
					'i' => $json_data['i'],
					'o' => (isset($json_data['o'])) ? $json_data['o'] : NULL,
					'birthday' => $json_data['birthday'],
					'address' => (isset($json_data['address'])) ? $json_data['address'] : NULL,
					'phone' => (isset($json_data['phone'])) ? $json_data['phone'] : NULL,
					'photo_id' => (isset($photo_id)) ? $photo_id : NULL
				];

		$this->db->insert('personal', $data);

		$personal_id = $this->db->insert_id();

		if (isset($photo_id)) {
			$this->db->where('id', $photo_id);
			$this->db->update('photo', ['personal_id' => $personal_id]);
		}

		if ($json_data['card'] > 0) {
			$this->db->where('id', $json_data['card']);
			$this->db->update('cards', ['holder_id' => $personal_id]);

			$this->ac_model->add_card($json_data['card']);
		}

		echo $personal_id;
	}

	public function update_pers() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$json_data = json_decode($this->input->post('data'), TRUE);

		if (isset($json_data['photo'])) {
			$this->db->select('id');
			$this->db->where('hash', $json_data['photo']);
			$query = $this->db->get('photo');
			$photo_id = $query->row()->id;
		}

		$data = [
					'class_id' => $json_data['class'],
					'f' => $json_data['f'],
					'i' => $json_data['i'],
					'o' => (isset($json_data['o'])) ? $json_data['o'] : NULL,
					'birthday' => $json_data['birthday'],
					'address' => (isset($json_data['address'])) ? $json_data['address'] : NULL,
					'phone' => (isset($json_data['phone'])) ? $json_data['phone'] : NULL,
					'photo_id' => (isset($photo_id)) ? $photo_id : NULL
				];

		$this->db->where('id', $json_data['id']);
		$this->db->update('personal', $data);

		if (isset($photo_id)) {
			$this->db->where('id', $photo_id);
			$this->db->update('photo', ['personal_id' => $json_data['id']]);
		}

		if ($json_data['card'] > 0) {
			$this->db->where('id', $json_data['card']);
			$this->db->update('cards', ['holder_id' => $json_data['id']]);

			$this->ac_model->add_card($json_data['card']);
		}

		echo $json_data['id'];
	}

	public function delete_pers() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$personal_id = $this->input->post('pers');

		if (!$personal_id) {
			return FALSE;
		}

		$cards = $this->ac_model->get_cards($personal_id);

		if ($cards) {
			foreach ($cards as $card) {
				$this->delete_card($card->id);
			}
		}

		$this->ac_model->delete_photo($personal_id);

		$this->db->delete('personal', ['id' => $personal_id]);

		if ($this->db->affected_rows() > 0) {
			echo $personal_id;
		} else {
			return FALSE;
		}

	}

	public function add_card() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$card_id = $this->input->post('card');
		$pers_id = $this->input->post('pers');


		if ($card_id && $pers_id) {
			$this->db->where('id', $card_id);
			$this->db->update('cards', ['holder_id' => $pers_id]);

			if ($this->ac_model->add_card($card_id)) {
				echo 'ok';
			} else {
				return FALSE;
			}

		} else {
			return FALSE;
		}

	}

	public function delete_card($card_id = NULL) {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$is_post = FALSE;

		if (!$card_id && $this->input->post('card')) {
			$card_id = $this->input->post('card');
			$is_post = TRUE;
		} else if (!$card_id) {
			return FALSE;
		}

		$school_id = $this->ac_model->get_school_by_user($this->user_id)->school_id;
		$controllers = $this->ac_model->get_controllers_by_school($school_id);

		$this->db->where('id', $card_id);
		$wiegand = $this->db->get('cards')->row()->wiegand;

		$this->db->where('id', $card_id);
		$this->db->update('cards', ['holder_id' => -1]);

		if ($this->db->affected_rows()) {
			foreach ($controllers as $c) {
				$this->ac_model->del_cards_from_controller($wiegand, $c->id);
			}

			if ($is_post) {
				echo 'ok';
			}

		}
	}

	public function save_class() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$number = $this->input->post('number');
		$letter = $this->input->post('letter');
		$school = $this->input->post('school');

		if (!$number || !$letter || !$school) {
			return FALSE;
		}

		$data = [
					'number' => $number,
					'letter' => $letter,
					'school_id' => $school
				];

		$this->db->insert('classes', $data);

		if ($this->db->affected_rows()) {
			echo json_encode($data);
		}

	}

	public function delete_class() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$class_id = $this->input->post('class');

		if (!$class_id) {
			return FALSE;
		}

		$this->db->where('id', $class_id);
		$this->db->delete('classes');

		if ($this->db->affected_rows()) {
			$this->db->where('class_id', $class_id);
			$this->db->update('personal', ['class_id' => NULL]);
			echo 'ok';
		}
	}

	public function get_classes() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$school_id = $this->ion_auth->user()->row()->school_id;

		$classes = $this->ac_model->get_classes_by_school($school_id);

		echo json_encode($classes);
	}

	public function get_pers() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$card = $this->input->post('card');
		$pers = $this->input->post('pers');

		if (!$pers) {
			$pers = $this->ac_model->get_pers_by_card($card);
			$class = $this->ac_model->get_class_by_id($pers->class);
			$pers->class = $class->number;
			$pers->class .= ' "';
			$pers->class .= $class->letter;
			$pers->class .= '"';
		} else {
			$this->db->select('address, birthday, f, i, o, phone');
			$this->db->select('personal.id AS \'id\'');
			$this->db->select('class_id AS \'class\'');
			$this->db->select('photo.hash AS \'photo\'');
			$this->db->where('personal.id', $pers);
			$this->db->join('photo', 'photo.id = personal.photo_id', 'left');
			$pers = $this->db->get('personal')->row();
		}

		if ($pers) {
			echo json_encode($pers);
		} else {
			return FALSE;
		}

	}

	public function get_cards() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->db->select('id, wiegand');
		$this->db->where('holder_id', -1);
		$query = $this->db->get('cards');

		echo json_encode($query->result());
	}

	public function get_cards_by_pers() {
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		if ($this->input->post('holder_id')) {
			$holder_id = $this->input->post('holder_id');
		} else {
			return FALSE;
		}

		$cards = $this->ac_model->get_cards($holder_id);

		if ($cards) {
			echo json_encode($cards);
		}
	}

}
?>

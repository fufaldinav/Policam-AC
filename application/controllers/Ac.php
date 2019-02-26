<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ac extends CI_Controller {

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
		} else if ($this->ion_auth->is_admin()) {
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

	public function add_pers() {
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
				$data['classes'][$row->id] = $row->number;
				$data['classes'][$row->id] .= ' "';
				$data['classes'][$row->id] .= $row->letter;
				$data['classes'][$row->id] .= '"';
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

	public function edit_pers() {
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
				$data['classes'][$row->id] = $row->number;
				$data['classes'][$row->id] .= ' "';
				$data['classes'][$row->id] .= $row->letter;
				$data['classes'][$row->id] .= '"';
				$data['menu'] .= '<li class="tree-node tree-is-root tree-expand-closed';
				$data['menu'] .= ($k == $last_k) ? ' tree-is-last' : '';
				$data['menu'] .= '"><div class="tree-expand"></div><div class="tree-content tree-expand-content">';
				$data['menu'] .= $data['classes'][$row->id];
				$data['menu'] .= '</div><ul class="tree-container">';
				$cur_class = $personal[$row->number.$row->letter]; //number + letter для сортировки дерева 1А -> 1Б -> 2А etc.
				$last_n = count($cur_class) - 1;
				foreach ($cur_class as $n => $pers) {
					$data['menu'] .= '<li id="pers';
					$data['menu'] .= $pers->pers_id;
					$data['menu'] .= '" class="tree-node tree-expand-leaf';
					$data['menu'] .= ($n == $last_n) ? ' tree-is-last' : '';
					$data['menu'] .= '"><div class="tree-expand"></div><div class="tree-content">';
					$data['menu'] .= ($pers->card_id) ? '(+) ' : '';
					$data['menu'] .= '<a class="pers" href="#';
					$data['menu'] .=	$pers->pers_id;
					$data['menu'] .=	'" onClick="getPersData(';
					$data['menu'] .=	$pers->pers_id;
					$data['menu'] .= ')">';
					$data['menu'] .= $pers->f;
					$data['menu'] .= ' ';
					$data['menu'] .= $pers->i;
					$data['menu'] .= '</a>';
					$data['menu'] .= '</div></li>';
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

	public function classes() {
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

		$delete = '<button onclick="save(';
		$delete .= $school_id;
		$delete .= ')">';
		$delete .= lang('save');
		$delete .= '</button>';
		$this->table->add_row(
								'<input id="number" type="text" size="2" maxlength="2" required />',
								'<input id="letter" type="text" size="1" maxlength="1" required />',
								$delete
							);

		foreach ($query->result() as $row) {
			$delete = '<button onclick="del(';
			$delete .= $row->id;
			$delete .= ')">';
			$delete .= lang('delete');
			$delete .= '</button>';
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

	public function save_pers() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$json_data = json_decode($this->input->post('data'), true);

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
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$json_data = json_decode($this->input->post('data'), true);

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
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
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
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
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
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
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
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
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
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
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
			redirect('auth/login');
		}

		$school_id = $this->ion_auth->user()->row()->school_id;

		$classes = $this->ac_model->get_classes_by_school($school_id);

		echo json_encode($classes);
	}

	public function get_pers() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
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

	public function get_time() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		echo time();
	}

	public function get_events() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}

		echo json_encode([
							'msgs' => $this->ac_model->start_polling(),
							// response again the server time to update the "js time variable"
							'time' => now('Asia/Yekaterinburg')
						]);
	}

	public function get_cards() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		$this->db->select('id, wiegand');
		$this->db->where('holder_id', -1);
		$query = $this->db->get('cards');

		echo json_encode($query->result());
	}

	public function get_cards_by_pers() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
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

	public function save_photo() {
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			if (isset($_FILES['file'])) {
				$file = $_FILES['file'];
				$error = '';
				$path = '/var/www/img_ac/';
				$extensions = ['jpg', 'jpeg'];

				$file_name = $file['name'];
				$file_tmp = $file['tmp_name'];
				$file_type = $file['type'];
				$file_size = $file['size'];


				$file_ext = explode('.', $file['name']);
				$file_ext = end($file_ext);
				$file_ext = strtolower($file_ext);
				$file_hash = hash_file('md5', $file_tmp);

				$file_path = $path;
				$file_path .= $file_hash;
				$file_path .= '.jpg';

				if (!in_array($file_ext, $extensions)) {
					$error = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
				}

				if ($file_size > 20971520) {
					$error = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
				}

				if ($file_size == 0) {
					$error = 'Wrong file or file not exists';
				}

				$response = [];
				if (!$error) {
					$time = now('Asia/Yekaterinburg');

					$this->db->where('personal_id', NULL);
					$this->db->where('time <', $time - 86400);
					$query = $this->db->get('photo');

					foreach ($query->result() as $row) {
						$this->ac_model->delete_photo(NULL, $row->hash);
					}

					$this->db->where('hash', $file_hash);
					$query = $this->db->get('photo');

					if ($query->num_rows() > 0) {
						$this->db->where('hash', $file_hash);
						$this->db->update('photo', ['time' => $time]);
					} else {
						$this->db->insert('photo', ['hash' => $file_hash, 'time' => $time]);
					}

					try {
						move_uploaded_file($file_tmp, $file_path);
						//сохранение уменьшенной копии
						$source_img = imagecreatefromjpeg($file_path);
						list($width, $height) = getimagesize($file_path);

						$file_path_s = $path;
						$file_path_s .= 's/';
						$file_path_s .= $file_hash;
						$file_path_s .= '.jpg';

						$d_w = $width / 240;
						$d_h = $height / 320;
						$d = max([$d_w, $d_h]);
						$new_w = $width / $d;
						$new_h = $height / $d;
						$dest_img = imagecreatetruecolor($new_w, $new_h);
						imagecopyresized($dest_img, $source_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
						imagejpeg($dest_img, $file_path_s);
						echo $file_hash;
					} catch (Exception $e) {
						$this->save_js_errors($e);
						echo '0';
					}
				}

				if ($error) {
					$this->save_js_errors($error);
					echo '0';
				}
			}

		}

	}

	public function delete_photo() {
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
		if (!$this->ion_auth->in_group(2)) {
			redirect('/');
		}

		if ($this->input->post('id') || $this->input->post('photo')) {
			$id = $this->input->post('id');
			$photo = $this->input->post('photo');
		} else {
			return FALSE;
		}

		if ($this->ac_model->delete_photo($id, $photo)) {
			echo 'ok';
		}
	}

	public function save_js_errors($err = NULL) {
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}

		$this->load->helper('file');

		if ($this->input->post('data')) {
			$err = $this->input->post('data');
		} else if ($err === NULL) {
			return FALSE;
		}

		$time = now('Asia/Yekaterinburg');
		$datestring = '%Y-%m-%d';
		$date = mdate($datestring, $time);
		$timestring = '%H:%i:%s';
		$time = mdate($timestring, $time);

		$path = '/var/www/logs';

		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}

		$path .= '/err-';
		$path .= $date;
		$path .= '.txt';

		$message = $time;
		$message .= ' ';
		$message .= $err;
		$message .= PHP_EOL;

		write_file($path, $message, 'a');
	}

	public function reload($controller_id = NULL) {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->is_admin()) {
			echo 'Нужно быть администратором';
			return;
		}
		if ($controller_id) {
			echo $this->ac_model->add_all_cards_to_controller($controller_id);
			echo ' заданий записано';
		} else {
			echo 'Не выбран контроллер';
		}
	}

}
?>

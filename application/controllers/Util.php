<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Util extends CI_Controller {

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

	public function door($controller_id = NULL, $open_time = NULL) {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		if (!$this->ion_auth->is_admin()) {
			echo 'Нужно быть администратором';
			return;
		}
		if ($controller_id) {
			echo $this->ac_model->set_door_params($controller_id, $open_time);
			echo ' заданий записано';
		} else {
			echo 'Не выбран контроллер';
		}
	}

}
?>

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Util
 * @property  Ac_model  $ac
 */
class Util extends CI_Controller
{
	/**
	* @var  int
	*/
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

	/**
	 * Получение времени сервера
	 */
	public function get_time()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		echo time();
	}

	/**
	 * Получение событий и начало long polling
	 */
	public function get_events()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		echo json_encode([
			'msgs' => $this->ac_model->start_polling(),
			'time' => now('Asia/Yekaterinburg')
		]);
	}

	/**
	 * Сохранение фотографии
	 */
	public function save_photo() //TODO проверка уже имеющейся фото
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_FILES['file'])) {
				$file = $_FILES['file'];
				$error = '';
				$IMG_PATH = '/var/www/img_ac';
				$extensions = ['jpg', 'jpeg'];

				$file_name = $file['name'];
				$file_tmp = $file['tmp_name'];
				$file_type = $file['type'];
				$file_size = $file['size'];


				$file_ext = explode('.', $file['name']);
				$file_ext = end($file_ext);
				$file_ext = strtolower($file_ext);
				$file_hash = hash_file('md5', $file_tmp);

				$file_path = "$IMG_PATH/$file_hash.jpg";

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

					$this->db->where('person_id', null);
					$this->db->where('time <', $time - 86400);
					$query = $this->db->get('photo');

					foreach ($query->result() as $row) {
						$this->ac_model->delete_photo(null, $row->hash);
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

						$file_path_s = "$IMG_PATH/s/$file_hash.jpg";

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

	/**
	 * Удаление фотографии
	 */
	public function delete_photo()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$person_id = $this->input->post('person_id');
		$photo = $this->input->post('photo');

		if (!isset($person_id) && !isset($photo)) {
			return null;
		}

		if ($this->ac_model->delete_photo($person_id, $photo)) {
			echo 'ok';
		}
	}

	/**
	 * Сохранение ошибок от клиентов
	 *
	 * @param  string  $err  Опционально, иначе обработка POST-запроса
	 */
	public function save_js_errors($err = null)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$LOG_PATH = '/var/www/logs';

		if (!is_dir($LOG_PATH)) {
			mkdir($LOG_PATH, 0777, true);
		}

		$this->load->helper('file');

		if ($err === null) {
			$err = $this->input->post('error');
		}

		if (!isset($err)) {
			return;
		}

		$time = now('Asia/Yekaterinburg');
		$datestring = '%Y-%m-%d';
		$date = mdate($datestring, $time);
		$timestring = '%H:%i:%s';
		$time = mdate($timestring, $time);

		$path = "$LOG_PATH/err-$date.txt";

		write_file($path, "$time $err\n", 'a');
	}

	/**
	 * Выгрузка всех карт в контроллер
	 *
	 * @param  int  $controller_id
	 */
	public function reload($controller_id = null)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}
		if (isset($controller_id)) {
			echo $this->ac_model->add_all_cards_to_controller($controller_id);
			echo ' заданий записано'; //TODO
		} else {
			echo 'Не выбран контроллер'; //TODO
		}
	}

	/**
	 * Установка времени открытия
	 *
	 * @param  int  $controller_id
	 * @param  int  $open_time
	 */
	public function door($controller_id = null, $open_time = null)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}
		if (isset($controller_id) && isset($open_time)) {
			echo $this->ac_model->set_door_params($controller_id, $open_time);
			echo ' заданий записано'; //TODO
		} else {
			echo 'Не выбран контроллер или не задано время открытия'; //TODO
		}
	}

	/**
	 * Обработка информации о проблеме с картой
	 */
	public function card_problem()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$type = $this->input->post('type');
		$person_id = $this->input->post('person_id');

		if (!isset($type) || !isset($person_id)) {
			return null;
		}

		$response = lang('registred');

		if ($type >= 1 && $type <= 3) {
			$pers = $this->ac_model->get_person($person_id);
		}

		if ($type == 1) {
			$desc = $pers->id . ' ' . $pers->f . ' ' . $pers->i . ' forgot card';

			if ($this->ac_model->add_user_event($type, $desc)) {
				echo $response;
			}
		} elseif ($type == 2 || $type == 3) {
			$cards = $this->ac_model->get_cards($person_id);

			if (!isset($cards)) {
				return null;
			}

			foreach ($cards as $card) {
				$this->ac_model->delete_card($card->id);
			}

			$desc = $pers->id . ' ' . $pers->f . ' ' . $pers->i . ' lost/broke card';

			$response .= ' ' . lang('and') . ' ' . lang('card_deleted');

			if ($this->ac_model->add_user_event($type, $desc)) {
				echo $response;
			}
		} else {
			return null;
		}
	}
}

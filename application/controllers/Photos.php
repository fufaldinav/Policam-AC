<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Photos
 * @property Photo_model $photo
 */
class Photos extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->load->model('ac/photo_model', 'photo');
	}

	/**
	 * Сохранение фотографии
	 */
	public function save()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_FILES['file'])) {
				$file = $_FILES['file'];
				header('Content-Type: application/json');

				echo json_encode(
					$this->photo->save($file)
				);
			}
		}
	}

	/**
	 * Удаление фотографии
	 *
	 * @param int $photo_id ID фотографии
	 */
	public function delete($photo_id)
	{
		echo $this->photo->delete($photo_id);
	}
}

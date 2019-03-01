<?php
/**
 * Name:    Photo Model
 * Author:  Artem Fufaldin
 *          artem.fufaldin@gmail.com
 * @m2jest1c
 *
 * Created:  01.03.2019
 *
 * Description:  Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.0 or above
 *
 * @package    Policam-AC
 * @author     Artem Fufaldin
 * @link       http://github.com/m2jest1c/Policam-AC
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
* Class Ac Model
*/
class Photo_model extends CI_Model
{
	/**
	* @var int
	*/
	public $id;

	/**
	* Каталог с фото
	*/
	const IMG_PATH = '/var/www/img_ac';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о фотографии
	*
	* @param   int      $id
	* @return  mixed[]
	*/
	public function get($id)
	{
		if ($id === null) {
			$id = $this->id;
		}

		$this->db->where('id', $id);
		$query = $this->db->get('photo');

		return $query->row();
	}

	/**
	* Получение информации о фотографии
	*
	* @param   string        $hash
	* @return  mixed[]|null
	*/
	public function get_by_hash($hash)
	{
		$this->db->where('hash', $hash);
		$query = $this->db->get('photo');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

		/**
		* Получение информации о фотографии
		*
		* @param   int           $person_id
		* @return  mixed[]|null
		*/
		public function get_by_person($person_id)
		{
			$this->db->where('person_id', $person_id);
			$query = $this->db->get('photo');

			if ($query->num_rows() > 0) {
				return $query->row();
			} else {
				return null;
			}
		}
	/**
	* Установить владельца фотографии
	*
	* @param   int  $person_id
	* @return  int
	*/
	public function set_person($person_id)
	{
		$this->db->where('id', $this->id);
		$this->db->update('photo', ['person_id' => $person_id]);

		return $this->db->affected_rows();
	}

	/**
	 * Сохранение фотографии
	 *
	 * @param   mixed[]
	 * @return  string
	 */
	public function save($file) //TODO проверка уже имеющейся фото
	{
		$response = [
			'id' => 0,
			'hash' => '',
			'error' => ''
		];

		$extensions = ['jpg', 'jpeg'];

		$file_name = $file['name'];
		$file_tmp = $file['tmp_name'];
		$file_type = $file['type'];
		$file_size = $file['size'];


		$file_ext = explode('.', $file['name']);
		$file_ext = end($file_ext);
		$file_ext = strtolower($file_ext);
		$file_hash = hash_file('md5', $file_tmp);

		if (!in_array($file_ext, $extensions)) {
			$response['error'] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
		}

		if ($file_size > 20971520) {
			$response['error'] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
		}

		if ($file_size == 0) {
			$response['error'] = 'Wrong file or file not exists';
		}

		if ($response['error'] === '') {
			$time = now('Asia/Yekaterinburg');

			$photo = $this->get_by_hash($file_hash);

			if ($photo === null) {
				$this->db->insert('photo', ['hash' => $file_hash, 'time' => $time]);
				$response['id'] = $this->db->insert_id();
				$response['hash'] = $file_hash;
			} else {
				$this->db->where('hash', $file_hash);
				$this->db->update('photo', ['time' => $time]);
				$response['id'] = $photo->id;
				$response['hash'] = $file_hash;
			}

			$this->clear_old();

			try {
				$file_path = self::IMG_PATH . "/$file_hash.jpg";
				move_uploaded_file($file_tmp, $file_path);
				//сохранение уменьшенной копии
				$source_img = imagecreatefromjpeg($file_path);
				list($width, $height) = getimagesize($file_path);
				$d = max([($width / 240), ($height / 320)]);
				$new_width = $width / $d;
				$new_height = $height / $d;
				$new_img = imagecreatetruecolor($new_width, $new_height);
				imagecopyresized($new_img, $source_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

				$file_path = self::IMG_PATH . "/s/$file_hash.jpg";
				imagejpeg($new_img, $file_path);

				return $response;
			} catch (Exception $e) {
				$response['error'] = $e;
				//$this->ac_model->save_js_errors($e);
				return $response;
			}
		} else {
			//$this->ac_model->save_js_errors($response['error']);
			return $response;
		}
	}

	/**
	 * Удаление фото из БД и диска
	 *
	 * @param   int        $photo_id
	 * @return  bool|null
	 */
	public function delete($photo_id)
	{
		$photo = $this->get($photo_id);

		if ($photo->person_id !== null) {
			$this->load->model('ac/person', 'person');

			$this->person->delete_photo($photo->person_id);
		}

		$this->db->delete('photo', ['id' => $photo->id]);

		try {
			$file_path = self::IMG_PATH . '/' . $photo->hash . '.jpg';
			if (file_exists($file_path)) {
				unlink($file_path);
			}

			$file_path = self::IMG_PATH . '/s/' . $photo->hash . '.jpg';
			if (file_exists($file_path)) {
				unlink($file_path);
			}

			return true;
		} catch (Exception $e) {
			//$this->ac_model->save_js_errors($e);
			return null;
		}
	}
	/**
	 * Очистка старых фото из БД и диска
	 */
	public function clear_old()
	{
		$this->db->where('person_id', null);
		$this->db->where('time <', now('Asia/Yekaterinburg') - 86400);
		$query = $this->db->get('photo');

		foreach ($query->result() as $photo) {
			$this->delete($photo->id);
		}
	}
}

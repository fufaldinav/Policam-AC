<?php
/**
 * Name:   Policam AC Photo Model
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.0 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
* Class Photo Model
* @property Person_model $person
* @property Util_model $util
*/
class Photo_model extends CI_Model
{
	/**
	* Каталог с фото
	*
	* @var string $img_path
	*/
	private $img_path;

	public function __construct()
	{
		parent::__construct();

		$this->config->load('ac', true);

		$this->img_path = $this->config->item('img_path', 'ac');

		if (!is_dir($this->img_path)) {
			mkdir($this->img_path, 0755, true);
		}
	}

	/**
	* Получение информации о фотографии по ID
	*
	* @param int $photo_id ID фотографии
	* @return object|null Фотография или NULL - отсутствует
	*/
	public function get($photo_id)
	{
		$query = $this->db
			->where('id', $photo_id)
			->get('photo');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о фотографии по хэшу
	*
	* @param string $hash Хэш-сумма фотографии
	* @return object|null Фотография или NULL - отсутствует
	*/
	public function get_by_hash($hash)
	{
		$query = $this->db
			->where('hash', $hash)
			->get('photo');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о фотографии по человеку
	*
	* @param int $person_id ID человека
	* @return object|null Фотография или NULL - отсутствует
	*/
	public function get_by_person($person_id)
	{
		$query = $this->db
			->where('person_id', $person_id)
			->get('photo');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}
	/**
	* Установить владельца фотографии
	*
	* @param int $photo_id ID фотографии
	* @param int $person_id ID человека
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function set_person($photo_id, $person_id)
	{
		$this->db
			->where('id', $photo_id)
			->update('photo', ['person_id' => $person_id]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Сохранение фотографии
	 *
	 * @param mixed[] $file Файл фотографии
	 * @return string
	 */
	public function save($file) //TODO проверка уже имеющейся фото за человеком
	{
		$response = [
			'id' => 0,
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
				$photo = $this->get($this->db->insert_id());
			} else {
				$this->db
					->where('hash', $file_hash)
					->update('photo', ['time' => $time]);
			}
			$response['id'] = $photo->id;

			$this->clear_old();

			try {
				$file_path = $this->img_path . '/' . $photo->id . '.jpg';
				move_uploaded_file($file_tmp, $file_path);
				//сохранение уменьшенной копии
				$source_img = imagecreatefromjpeg($file_path);
				list($width, $height) = getimagesize($file_path);
				$d = max([($width / 240), ($height / 320)]);
				$new_width = $width / $d;
				$new_height = $height / $d;
				$new_img = imagecreatetruecolor($new_width, $new_height);
				imagecopyresized($new_img, $source_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

				$file_path = $this->img_path . '/s/' . $photo->id . '.jpg';
				imagejpeg($new_img, $file_path);

				return $response;
			} catch (Exception $e) {
				$response['error'] = $e;
				$this->load->model('ac/util_model', 'util');
				$this->util->save_errors($e);
				return $response;
			}
		} else {
			$this->load->model('ac/util_model', 'util');
			$this->util->save_errors($e);
			return $response;
		}
	}

	/**
	 * Удаление фото из БД и диска
	 *
	 * @param int $photo_id ID фотографии
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function delete($photo_id)
	{
		$photo = $this->get($photo_id);

		if ($photo->person_id !== null) {
			$this->load->model('ac/person_model', 'person');

			$this->person->delete_photo($photo->person_id);
		}

		$this->db->delete('photo', ['id' => $photo->id]);

		try {
			$file_path = $this->img_path . '/' . $photo->id . '.jpg';
			if (file_exists($file_path)) {
				unlink($file_path);
			}

			$file_path = $this->img_path . '/s/' . $photo->id . '.jpg';
			if (file_exists($file_path)) {
				unlink($file_path);
			}

			return true;
		} catch (Exception $e) {
			$this->load->model('ac/util_model', 'util');
			$this->util->save_errors($e);
			return false;
		}
	}
	/**
	 * Очистка старых фото из БД и диска
	 */
	public function clear_old()
	{
		$query =  $this->db
			->where('person_id', null)
			->where('time <', now('Asia/Yekaterinburg') - 86400)
			->get('photo');

		foreach ($query->result() as $photo) {
			$this->delete($photo->id);
		}
	}
}

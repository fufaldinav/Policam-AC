<?php
/**
 * Name:   Policam AC Person Model
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
* Class Person Model
*/
class Person_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о человеке по ID
	*
	* @param int $person_id ID человека
	* @return object[]|null Человек или NULL - отсутствует
	*/
	public function get($person_id)
	{
		$query = $this->db->select('id, address, birthday, f, i, o, phone, type')
			->select("div_id AS 'div'")
			->select("photo_id AS 'photo'")
			->where('id', $person_id)
			->get('persons');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о всех людях
	*
	* @param int|null $div_id ID подразделения
	* @return object[]|null Массив с людьми или NULL - отсутствует
	*/
	public function get_all($div_id = null)
	{
		if ($div_id !== null) {
			$this->db->where('div_id', $div_id);
		}
		$query = $this->db
			->order_by('f', 'ASC')
			->order_by('i', 'ASC')
			->order_by('o', 'ASC')
			->get('persons');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Добавление нового человека
	*
	* @param object $person Человек
	* @return int ID нового человека
	*/
	public function add($person)
	{
		$this->db->insert('persons', $this->set($person));

		return $this->db->insert_id();
	}

	/**
	* Обновление информации о человеке
	*
	* @param object $person Человек
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function update($person)
	{
		$this->db
			->where('id', $person->id)
			->update('persons', $this->set($person));

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Удаление человека
	*
	* @param int $person_id ID человека
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function delete($person_id)
	{
		$this->db->delete('persons', ['id' => $person_id]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Установить информацию о человеке
	*
	* @param object $person Человек
	* @return mixed[] Массив с параметрами человека
	*/
	public function set($person)
	{
		$data = [
			'div_id' => $person->div,
			'f' => $person->f,
			'i' => $person->i,
			'o' => $person->o,
			'birthday' => $person->birthday,
			'address' => $person->address,
			'phone' => $person->phone,
			'photo_id' => $person->photo
		];

		return $data;
	}

	/**
	* Удалить инфомации о фотографии
	*
	* @param int $person_id ID человека
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function delete_photo($person_id)
	{
		$this->db
			->where('id', $person_id)
			->update('persons', ['photo_id' => null]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}

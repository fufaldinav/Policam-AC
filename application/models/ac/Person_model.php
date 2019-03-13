<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.2 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
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

		$this->load->database();
	}

	/**
	 * Получает человека по ID
	 *
	 * @param int $person_id ID человека
	 *
	 * @return object|null Человек или NULL, если не найдена
	 */
	public function get(int $person_id): ?object
	{
		$query = $this->db->select('id, address, birthday, f, i, o, phone, type')
			->select("div_id AS 'div'")
			->select("photo_id AS 'photo'")
			->where('id', $person_id)
			->get('persons');

		return $query->row();
	}

	/**
	 * Получает список всех людей по подразделению
	 *
	 * @param int|null $div_id ID подразделения
	 *
	 * @return object[] Массив с людьми или пустой массив
	 */
	public function get_all(int $div_id = null): array
	{
		if (isset($div_id)) {
			$this->db->where('div_id', $div_id);
		}
		$query = $this->db
			->order_by('f ASC, i ASC, o ASC')
			->get('persons');

		return $query->result();
	}

	/**
	 * Добавляет нового человека
	 *
	 * @param object $person Человек
	 *
	 * @return int ID нового человека
	 */
	public function add(object $person): int
	{
		$this->db->insert('persons', $this->_set($person));

		return $this->db->insert_id();
	}

	/**
	 * Обновляет информацию о человеке
	 *
	 * @param object $person Человек
	 *
	 * @return int Количество успешных записей
	 */
	public function update(object $person): int
	{
		$this->db
			->where('id', $person->id)
			->update('persons', $this->_set($person));

		return $this->db->affected_rows();
	}

	/**
	 * Удаляет человека
	 *
	 * @param int $person_id ID человека
	 *
	 * @return int Количество успешных удалений
	 */
	public function delete(int $person_id): int
	{
		$this->db->delete('persons', ['id' => $person_id]);

		return $this->db->affected_rows();
	}

	/**
	 * Получает объект и возвращает массив для записи
	 *
	 * @param object $person Человек
	 *
	 * @return mixed[] Массив с параметрами человека
	 */
	private function _set(object $person): array
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
	 * Удаляет информацию о фотографии
	 *
	 * @param int $person_id ID человека
	 *
	 * @return int Количество успешных удалений
	 */
	public function unset_photo(int $person_id): int
	{
		$this->db
			->where('id', $person_id)
			->update('persons', ['photo_id' => null]);

		return $this->db->affected_rows();
	}
}

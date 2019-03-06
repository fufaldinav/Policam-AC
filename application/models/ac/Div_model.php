<?php
/**
 * Name:   Policam AC Div Model
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 02.03.2019
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
* Class Div Model
*/
class Div_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о подразделении
	*
	* @param int $div_id ID подразделения
	* @return object|null Подразделение или NULL - отсутствует
	*/
	public function get(int $div_id)
	{
		$query = $this->db
			->where('id', $div_id)
			->get('divisions');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о всех подразделениях
	*
	* @param int|null $org_id ID организации
	* @return object[]|null Массив с подразделениями или NULL - отсутствует
	*/
	public function get_all(int $org_id = null)
	{
		if ($org_id !== null) {
			$this->db->where('org_id', $org_id);
		}
		$query = $this->db
			->order_by('number', 'ASC')
			->order_by('letter', 'ASC')
			->get('divisions');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Добавление нового подразделения
	*
	* @param object $div Подразделение
	* @return int ID нового подразделения
	*/
	public function add($div): int
	{
		$this->db->insert('divisions', $this->set($div));

		return $this->db->insert_id();
	}

	/**
	* Обновление информации о подразделении
	*
	* @param object $div Подразделение
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function update($div): bool
	{
		$this->db
			->where('id', $div->id)
			->update('divisions', $this->set($div));

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Удаление подразделения
	*
	* @param int $div_id ID подразделения
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function delete(int $div_id): bool
	{
		$this->db->delete('divisions', ['id' => $div_id]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Установить информацию о подразделении
	*
	* @param object $div Подразделение
	* @return mixed[] Массив с параметрами контроллера
	*/
	public function set($div): array
	{
		$data = [
			'number' => $div->number,
			'letter' => $div->letter,
			'org_id' => $div->org_id
		];

		return $data;
	}
}

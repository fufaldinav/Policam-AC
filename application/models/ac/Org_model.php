<?php
/**
 * Name:   Policam AC Org Model
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
* Class Org Model
*/
class Org_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о организации
	*
	* @param int $org_id ID организации
	* @return object|null Организация или NULL - отсутствует
	*/
	public function get($org_id)
	{
		$query = $this->db
			->where('id', $org_id)
			->get('organizations');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о всех организациях
	*
	* @param int|null $user_id ID пользователя
	* @return object[]|null Массив с организациями или NULL - отсутствует
	*/
	public function get_all($user_id = null)
	{
		if ($user_id !== null) {
			$this->db->where('user_id', $user_id);
		}
		$query = $this->db
			->join('organizations', 'organizations.id = organizations_users.org_id', 'left')
			->order_by('number', 'ASC')
			->get('organizations_users');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	 * Получить полное имя организации
	 *
	 * @param int $org_id ID организации
	 * @return string|null Строка в формате 'номер (адресс при наличии)' или NULL - оргиназиция отсутствует
	 */
	public function get_full_name($org_id)  //TODO check
	{
		$org = $this->get($org_id);

		if ($org === null) {
			return null;
		}

		$org_name = $org->number;
		if ($org->address !== null) {
			$org_name .= ' (' . $org->address . ')';
		}

		return $org_name;
	}

	/**
	* Добавление новой организации
	*
	* @param object $org Организация
	* @return int ID новой организации
	*/
	public function add($org)
	{
		$this->db->insert('organizations', $this->set($org));

		return $this->db->insert_id();
	}

	/**
	* Обновление информации об организациях
	*
	* @param object $org Организация
	* @return int TRUE - успешно, FALSE - ошибка
	*/
	public function update($org)
	{
		$this->db
			->where('id', $id)
			->update('organizations', $this->set($org));

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Удаление организации
	*
	* @param int $org_id ID организации
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function delete($org_id)
	{
		$this->db->delete('organizations', ['id' => $org_id]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Установить информацию об организации
	*
	* @param object $org Организация
	* @return mixed[] Массив с параметрами организации
	*/
	public function set($org)
	{
		$this->data = [
			'number' => $org->number,
			'addres' => $org->address,
			'type' => $org->type
		];

		return $data;
	}
}

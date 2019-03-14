<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 02.03.2019
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
 * Class Org Model
 */
class Org_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
	}

	/**
	 * Получает организацию по ID
	 *
	 * @param int $org_id ID организации
	 *
	 * @return object|null Организация или NULL, если не найдена
	 */
	public function get(int $org_id): ?object
	{
		$query = $this->db
			->where('id', $org_id)
			->get('organizations');

		return $query->row();
	}

	/**
	 * Получает все организации по пользователю
	 *
	 * @param int|null $user_id ID пользователя
	 *
	 * @return object[] Массив с организациями или пустой массив
	 */
	public function get_list(int $user_id = null): array
	{
		if (isset($user_id)) {
			$this->db->where('user_id', $user_id);
		}
		$query = $this->db
			->join('organizations', 'organizations.id = organizations_users.org_id', 'left')
			->order_by('name', 'ASC')
			->get('organizations_users');

		return $query->result();
	}

	/**
	 * Получает пользователей организации
	 *
	 * @param int $org_id ID орагнизации
	 *
	 * @return int[] Список ID пользователей
	 */
	public function get_users(int $org_id): array
	{
		$query = $this->db
			->select('user_id')
			->where('org_id', $org_id)
			->get('organizations_users');

		return $query->result();
	}

	/**
	 * Получает полное имя организации
	 *
	 * @param int $org_id ID организации
	 *
	 * @return string Строка в формате 'номер (адресс при наличии)' или NULL, если организация не найдена
	 */
	public function get_full_name(int $org_id): string  //TODO check
	{
		$org = $this->get($org_id);

		$org_name = $org->name;
		if (isset($org->address)) {
			$org_name .= " ($org->address)";
		}

		return $org_name;
	}

	/**
	 * Добавляет новую организацию
	 *
	 * @param object $org Организация
	 *
	 * @return int ID новой организации
	 */
	public function add(object $org): int
	{
		$this->db->insert('organizations', $this->_set($org));

		return $this->db->insert_id();
	}

	/**
	 * Обновляет информацию об организации
	 *
	 * @param object $org Организация
	 *
	 * @return int Количество успешных записей
	 */
	public function update(object $org): int
	{
		$this->db
			->where('id', $id)
			->update('organizations', $this->_set($org));

		return $this->db->affected_rows();
	}

	/**
	 * Удаляет организацию
	 *
	 * @param int $org_id ID организации
	 *
	 * @return int Количество успешных удалений
	 */
	public function delete(int $org_id): int
	{
		$this->db->delete('organizations', ['id' => $org_id]);

		return $this->db->affected_rows();
	}

	/**
	 * Получает объект и возвращает массив для записи
	 *
	 * @param object $org Организация
	 *
	 * @return mixed[] Массив с параметрами организации
	 */
	private function _set(object $org): array
	{
		$this->data = [
			'name' => $org->name,
			'addres' => $org->address,
			'type' => $org->type ?? 1
		];

		return $data;
	}
}

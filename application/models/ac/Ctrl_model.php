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
 * Class Ctrl Model
 */
class Ctrl_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
	}

	/**
	 * Получает контроллер по ID
	 *
	 * @param int $ctrl_id ID контроллера
	 *
	 * @return object|null Контроллер или NULL, если не найден
	 */
	public function get(int $ctrl_id): ?object
	{
		$query = $this->db
			->where('id', $ctrl_id)
			->get('controllers');

		return $query->row();
	}

	/**
	 * Получает контроллер по серийному номеру
	 *
	 * @param int $sn Серийный номер контроллера
	 *
	 * @return object|null Контроллер или NULL - отсутствует
	 */
	public function get_by_sn(int $sn): ?object
	{
		$query = $this->db
			->where('sn', $sn)
			->get('controllers');

		return $query->row();
	}

	/**
	 * Получает список всех контроллеров по организации
	 *
	 * @param int|null $org_id ID организации
	 *
	 * @return object[] Массив с контроллерами или пустой массив
	 */
	public function get_all(int $org_id = null): array
	{
		if (isset($org_id)) {
			$this->db->where('org_id', $org_id);
		}
		$query = $this->db->get('controllers');

		return $query->result();
	}

	/**
	 * Добавляет новый контроллер
	 *
	 * @param object $ctrl Контроллер
	 *
	 * @return int ID нового контроллера
	 */
	public function add(object $ctrl): int
	{
		$this->db->insert('controllers', $this->_set($ctrl));

		return $this->db->insert_id();
	}

	/**
	 * Обновляет информацию о контроллере
	 *
	 * @param object $ctrl Контроллер
	 *
	 * @return int Количество успешных записей
	 */
	public function update(object $ctrl): int
	{
		$this->db
			->where('id', $ctrl->id)
			->update('controllers', $this->_set($ctrl));

		return $this->db->affected_rows();
	}

	/**
	 * Удаляет контроллер
	 *
	 * @param int $ctrl_id ID контроллера
	 *
	 * @return int Количество успешных удалений
	 */
	public function delete(int $ctrl_id): int
	{
		$this->db->delete('controllers', ['id' => $ctrl_id]);

		return $this->db->affected_rows();
	}

	/**
	 * Получает объект и возвращает массив для записи
	 *
	 * @param object $ctrl Контроллер
	 *
	 * @return array Массив с параметрами контроллера
	 */
	private function _set(object $ctrl): array
	{
		$data = [
			'name' => $ctrl->name,
			'online' => $ctrl->online,
			'last_conn' => $ctrl->last_conn,
			'org_id' => $ctrl->org_id
		];

		return $data;
	}
}

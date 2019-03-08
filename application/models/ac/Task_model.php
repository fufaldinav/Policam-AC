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
* Class Task Model
*/
class Task_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Добавляет задания для отправки на контроллер
	 *
	 * @param string $operation Операция, отправляемая на контроллер
	 * @param int $ctrl_id      ID контроллера
	 * @param string|null $data Дополнительные данные
	 * @return int Количество успешных записей
	 */
	public function add(string $operation, int $ctrl_id, string $data = null): int
	{
		$id = mt_rand(500000, 999999999);

		$json = '{"id":' . $id . ',';
		$json .= '"operation":"' . $operation . '"';
		$json .= isset($data) ? ',' : '';
		$json .= isset($data) ? $data : '';
		$json .= '}';

		$data =	[
			'id' => $id,
			'controller_id' => $ctrl_id,
			'json' => $json,
			'time' => now('Asia/Yekaterinburg')
		];

		$this->db->insert('tasks', $data);

		return $this->db->affected_rows();
	}

	/**
	 * Удаляет задания, отправленные на контроллер
	 *
	 * @param int $task_id ID задания
	 * @return int Количество успешных удалений
	 */
	public function delete(int $task_id): int
	{
		$this->db
			->where('id', $task_id)
			->delete('tasks');

		return $this->db->affected_rows();
	}

	/**
	 * Получает последнее задание для отправки на контроллер
	 *
	 * @param int $ctrl_id ID контроллера
	 * @return object|null Задание или NULL, если не найден
	 */
	public function get_last(int $ctrl_id): ?object
	{
		$query = $this->db
			->where('controller_id', $ctrl_id)
			->order_by('time', 'ASC')
			->get('tasks');

		return $query->row();
	}
}

<?php
/**
 * Name:   Policam AC Task Model
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
* Class Task Model
*/
class Task_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Добавление задания для отправки на контроллер
	 *
	 * @param int $operation  Операция, отправляемая на контроллер
	 * @param int $ctrl_id    ID контроллера
	 * @param int|null $data  Дополнительные данные
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function add($operation, $ctrl_id, $data = null)
	{
		$id = mt_rand(500000, 999999999);

		$json = '{"id":' . $id . ',';
		$json .= '"operation":"' . $operation . '"';
		$json .= (isset($data)) ? ',' : '';
		$json .= (isset($data)) ? $data : '';
		$json .= '}';

		$data =	[
			'id' => $id,
			'controller_id' => $ctrl_id,
			'json' => $json,
			'time' => now('Asia/Yekaterinburg')
		];

		$this->db->insert('tasks', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Удаление задания, отправленного на контроллер
	 *
	 * @param int $task_id ID задания
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function delete($task_id)
	{
		$this->db
			->where('id', $task_id)
			->delete('tasks');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Получение последнего задания для отправки на контроллер
	 *
	 * @param int $ctrl_id ID контроллера
	 * @return object|null Задание или NULL - отсутствует
	 */
	public function get_last($ctrl_id)
	{
		$query = $this->db
			->where('controller_id', $ctrl_id)
			->order_by('time', 'ASC')
			->get('tasks');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}
}

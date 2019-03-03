<?php
/**
 * Name:    Task Model
 * Author:  Artem Fufaldin
 *          artem.fufaldin@gmail.com
 *
 * Created:  02.03.2019
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
	 * @param int $operation     Операция, отправляемая на контроллер
	 * @param int $controller_id ID контроллера
	 * @param int|null $data     Дополнительные данные
	 * @return int
	 */
	public function add($operation, $controller_id, $data = null)
	{
		$id = mt_rand(500000, 999999999);

		$json = '{"id":' . $id . ',';
		$json .= '"operation":"' . $operation . '"';
		$json .= (isset($data)) ? ',' : '';
		$json .= (isset($data)) ? $data : '';
		$json .= '}';

		$data =	[
			'id' => $id,
			'controller_id' => $controller_id,
			'json' => $json,
			'time' => now('Asia/Yekaterinburg')
		];

		$this->db->insert('tasks', $data);

		return $this->db->affected_rows();
	}

	/**
	 * Удаление задания, отправленного на контроллер
	 *
	 * @param int $task_id ID задания
	 * @return int
	 */
	public function delete($task_id)
	{
		$this->db->where('id', $task_id);
		$this->db->delete('tasks');

		return $this->db->affected_rows();
	}

	/**
	 * Получение последнего задания для отправки на контроллер
	 *
	 * @param int $controller_id ID контроллера
	 * @return mixed[]|bool
	 */
	public function get_last($controller_id)
	{
		$this->db->where('controller_id', $controller_id);
		$this->db->order_by('time', 'ASC');
		$query = $this->db->get('tasks');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}
}

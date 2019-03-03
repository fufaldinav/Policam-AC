<?php
/**
 * Name:   Ctrl Model
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
* Class Ctrl Model
* @property Task_model $task
*/
class Ctrl_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о контроллере
	*
	* @param int $ctrl_id ID контроллера
	* @return mixed[]
	*/
	public function get($ctrl_id)
	{
		$this->db->where('id', $ctrl_id);
		$query = $this->db->get('controllers');

		return $query->row();
	}

	/**
	* Получение информации о всех контроллерах организации
	*
	* @param int|null $org_id ID организации, по-умолчанию все контроллеры в БД
	* @return mixed[]
	*/
	public function get_all($org_id = null)
	{
		if ($org_id !== null) {
			$this->db->where('org_id', $org_id);
		}
		$query = $this->db->get('controllers');

		return $query->result();
	}

	/**
	* Добавление нового контроллера
	*
	* @param object $ctrl Контроллер
	* @return int
	*/
	public function add($ctrl)
	{
		$this->db->insert('controllers', $this->set($ctrl));
		$ctrl_id = $this->db->insert_id();

		return $ctrl_id;
	}

	/**
	* Обновление информации о контроллере
	*
	* @param object $ctrl Контроллер
	* @return int
	*/
	public function update($ctrl)
	{
		$this->db->where('id', $ctrl->id);
		$this->db->update('controllers', $this->set($ctrl));

		return $this->db->affected_rows();
	}

	/**
	* Удаление контроллера
	*
	* @param int $ctrl_id ID контроллера
	* @return int
	*/
	public function delete($ctrl_id)
	{
		$this->db->delete('controllers', ['id' => $ctrl_id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию о контроллере
	*
	* @param object $ctrl Контроллер
	* @return mixed[]
	*/
	public function set($ctrl)
	{
		$this->data = [
			'name' => $ctrl->name,
			'online' => $ctrl->online,
			'org_id' => $ctrl->org_id
		];

		return $data;
	}

	/**
	 * Установить параметры открытия
	 *
	 * @param int $ctrl_id ID контроллера
	 * @param int $open_time     Время открытия в 0.1 сек
	 * @param int $open_control  Контроль открытия в 0.1 сек, по-умолчанию 0 - без контроля
	 * @param int $close_control Контроль закрытия в 0.1 сек, по-умолчанию 0 - без контроля
	 * @return int
	 */
	public function set_door_params($ctrl_id, $open_time, $open_control = 0, $close_control = 0)
	{
		$this->load->model('ac/task_model', 'task');

		$data = '"open":' . $open_time;
		$data .= ',"open_control":' . $open_control;
		$data .= ',"close_control":' . $close_control;

		return $this->task->add('set_door_params', $ctrl_id, $data);
	}

	/**
	 * Добавление карт в контроллер
	 *
	 * @param int $ctrl_id     ID контроллера
	 * @param string[]|string $cards Карты (код)
	 * @return int
	 */
	public function add_cards($ctrl_id, $cards)
	{
		$this->load->model('ac/task_model', 'task');

		$data = '"cards": [';
		if (is_array($cards)) {
			foreach ($cards as $card) {
				$data .= '{"card":"' . $card . '","flags":32,"tz":255},';
			}
			$data = substr($data, 0, -1);
		} else {
			$data .= '{"card":"' . $cards . '","flags":32,"tz":255}';
		}
		$data .= ']';

		return $this->task->add('add_cards', $ctrl_id, $data);
	}

	/**
	 * Удаление карт из контроллера
	 *
	 * @param int $ctrl_id     ID контроллера
	 * @param string[]|string $cards Карты (код)
	 * @return int
	 */
	public function delete_cards($ctrl_id, $cards)
	{
		$this->load->model('ac/task_model', 'task');

		$data = '"cards": [';
		if (is_array($cards)) {
			foreach ($cards as $card) {
				$data .= '{"card":"' . $card . '"},';
			}
			$data = substr($data, 0, -1);
		} else {
			$data .= '{"card":"' . $cards . '"}';
		}
		$data .= ']';

		return $this->task->add('del_cards', $ctrl_id, $data);
	}


	/**
	 * Удаление всех карт из контроллера
	 *
	 * @param int $ctrl_id ID контроллера
	 * @return int
	 */
	public function clear_cards($ctrl_id)
	{
		$this->load->model('ac/task_model', 'task');

		return $this->task->add('clear_cards', $ctrl_id, $data);
	}
}

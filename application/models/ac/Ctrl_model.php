<?php
/**
 * Name:   Policam AC Ctrl Model
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
	* Получение информации о контроллере по ID
	*
	* @param int $ctrl_id ID контроллера
	* @return object|null Контроллер или NULL - отсутствует
	*/
	public function get($ctrl_id)
	{
		$query = $this->db
			->where('id', $ctrl_id)
			->get('controllers');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о контроллере по серийному номеру
	*
	* @param string $sn Серийный номер контроллера
	* @return object|null Контроллер или NULL - отсутствует
	*/
	public function get_by_sn($sn)
	{
		$query = $this->db
			->where('sn', $sn)
			->get('controllers');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о всех контроллерах
	*
	* @param int|null $org_id ID организации
	* @return object[]|null Массив с контроллерами или NULL - отсутствует
	*/
	public function get_all($org_id = null)
	{
		if ($org_id !== null) {
			$this->db->where('org_id', $org_id);
		}
		$query = $this->db->get('controllers');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Добавление нового контроллера
	*
	* @param object $ctrl Контроллер
	* @return int ID нового контроллера
	*/
	public function add($ctrl)
	{
		$this->db->insert('controllers', $this->set($ctrl));

		return $this->db->insert_id();
	}

	/**
	* Обновление информации о контроллере
	*
	* @param object $ctrl Контроллер
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function update($ctrl)
	{
		$this->db
			->where('id', $ctrl->id)
			->update('controllers', $this->set($ctrl));

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Удаление контроллера
	*
	* @param int $ctrl_id ID контроллера
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function delete($ctrl_id)
	{
		$this->db->delete('controllers', ['id' => $ctrl_id]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Установить информацию о контроллере
	*
	* @param object $ctrl Контроллер
	* @return mixed[] Массив с параметрами контроллера
	*/
	public function set($ctrl)
	{
		$data = [
			'name' => $ctrl->name,
			'online' => $ctrl->online,
			'last_conn' => $ctrl->last_conn,
			'org_id' => $ctrl->org_id
		];

		return $data;
	}

	/**
	 * Установить параметры открытия
	 *
	 * @param int $ctrl_id       ID контроллера
	 * @param int $open_time     Время открытия в 0.1 сек
	 * @param int $open_control  Контроль открытия в 0.1 сек, по-умолчанию 0 - без контроля
	 * @param int $close_control Контроль закрытия в 0.1 сек, по-умолчанию 0 - без контроля
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function set_door_params($ctrl_id, $open_time, $open_control = 0, $close_control = 0)
	{
		$this->load->model('ac/task_model', 'task');

		$data = '"open":' . $open_time;
		$data .= ',"open_control":' . $open_control;
		$data .= ',"close_control":' . $close_control;

		if ($this->task->add('set_door_params', $ctrl_id, $data)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Добавление карт в контроллер
	 *
	 * @param int $ctrl_id           ID контроллера
	 * @param string[]|string $codes Коды карт
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function add_cards($ctrl_id, $codes)
	{
		$this->load->model('ac/task_model', 'task');

		$data = '"cards": [';
		if (is_array($codes)) {
			foreach ($codes as $code) {
				$data .= '{"card":"' . $code . '","flags":32,"tz":255},';
			}
			$data = substr($data, 0, -1);
		} else {
			$data .= '{"card":"' . $codes . '","flags":32,"tz":255}';
		}
		$data .= ']';

		if ($this->task->add('add_cards', $ctrl_id, $data)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Удаление карт из контроллера
	 *
	 * @param int $ctrl_id           ID контроллера
	 * @param string[]|string $codes Коды карт
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function delete_cards($ctrl_id, $codes)
	{
		$this->load->model('ac/task_model', 'task');

		$data = '"cards": [';
		if (is_array($codes)) {
			foreach ($codes as $code) {
				$data .= '{"card":"' . $code . '"},';
			}
			$data = substr($data, 0, -1);
		} else {
			$data .= '{"card":"' . $codes . '"}';
		}
		$data .= ']';

		if ($this->task->add('del_cards', $ctrl_id, $data)) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Удаление всех карт из контроллера
	 *
	 * @param int $ctrl_id ID контроллера
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function clear_cards($ctrl_id)
	{
		$this->load->model('ac/task_model', 'task');

		if ($this->task->add('clear_cards', $ctrl_id)) {
			return true;
		} else {
			return false;
		}
	}
}

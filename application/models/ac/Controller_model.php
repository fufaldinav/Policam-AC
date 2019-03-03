<?php
/**
 * Name:   Controller Model
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created:  02.03.2019
 *
 * Description:  Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.0 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
* Class Controller Model
*/
class Controller_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о контроллере
	*
	* @param int $controller_id ID контроллера
	* @return mixed[]
	*/
	public function get($controller_id)
	{
		$this->db->where('id', $controller_id);
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
	* @param object $controller Контроллер
	* @return int
	*/
	public function add($controller)
	{
		$this->db->insert('controllers', $this->set($controller));
		$controller_id = $this->db->insert_id();

		return $controller_id;
	}

	/**
	* Обновление информации о контроллере
	*
	* @param object $controller Контроллер
	* @return int
	*/
	public function update($controller)
	{
		$this->db->where('id', $controller->id);
		$this->db->update('controllers', $this->set($controller));

		return $this->db->affected_rows();
	}

	/**
	* Удаление контроллера
	*
	* @param int $controller_id ID контроллера
	* @return int
	*/
	public function delete($controller_id)
	{
		$this->db->delete('controllers', ['id' => $controller_id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию о контроллере
	*
	* @param object $controller Контроллер
	* @return mixed[]
	*/
	public function set($controller)
	{
		$this->data = [
			'name' => $controller->name,
			'online' => $controller->online,
			'org_id' => $controller->org_id
		];

		return $data;
	}

	/**
	 * Установить параметры открытия
	 *
	 * @param int $controller_id ID контроллера
	 * @param int $open_time     Время открытия в 0.1 сек
	 * @param int $open_control  Контроль открытия в 0.1 сек, по-умолчанию 0 - без контроля
	 * @param int $close_control Контроль закрытия в 0.1 сек, по-умолчанию 0 - без контроля
	 * @return int
	 */
	public function set_door_params($controller_id, $open_time, $open_control = 0, $close_control = 0)
	{
		$this->load->model('ac/task_model', 'task');

		$data = '"open":' . $open_time;
		$data .= ',"open_control":' . $open_control;
		$data .= ',"close_control":' . $close_control;

		return $this->task->add('set_door_params', $controller_id, $data);
	}

	/**
	 * Добавление карт в контроллер
	 *
	 * @param int $controller_id     ID контроллера
	 * @param string[]|string $cards Карты (код)
	 * @return int
	 */
	public function add_cards($controller_id, $cards)
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

		return $this->task->add('add_cards', $controller_id, $data);
	}

	/**
	 * Удаление карт из контроллера
	 *
	 * @param int $controller_id     ID контроллера
	 * @param string[]|string $cards Карты (код)
	 * @return int
	 */
	public function delete_cards($controller_id, $cards)
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

		return $this->task->add('del_cards', $controller_id, $data);
	}


	/**
	 * Удаление всех карт из контроллера
	 *
	 * @param int $controller_id ID контроллера
	 * @return int
	 */
	public function clear_cards($controller_id)
	{
		$this->load->model('ac/task_model', 'task');

		return $this->task->add('clear_cards', $controller_id, $data);
	}
}

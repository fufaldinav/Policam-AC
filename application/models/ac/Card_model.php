<?php
/**
 * Name:   Card Model
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created:  01.03.2019
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
* Class Ac Model
*/
class Card_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о карте
	*
	* @param int $card_id ID карты
	* @return object
	*/
	public function get($card_id)
	{
		$this->db->where('id', $card_id);
		$query = $this->db->get('cards');

		return $query->row();
	}

	/**
	* Получение списка карт, привязаных к конкретному человеку
	*
	* @param int $holder_id ID человека, по-умолчанию -1 (список всех неизвестных карт)
	* @return mixed[]|null
	*/
	public function get_by_holder($holder_id = -1)
	{
		$this->db->where('holder_id', $holder_id);
		$query = $this->db->get('cards');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о всех картах контроллера
	*
	* @param int|null $controller_id ID контроллера, по-умолчанию все карты
	* @return mixed[]
	*/
	public function get_all($controller_id = null)
	{
		if (isset($controller_id)) {
			$this->db->where('controller_id', $controller_id);
		}
		$query = $this->db->get('cards');

		return $query->result();
	}

	/**
	* Добавление карты
	*
	* @param int $card_id   ID карты
	* @param int $person_id ID человека
	* @return int
	*/
	public function set_holder($card_id, $person_id)
	{
		$this->load->model('ac/controller_model', 'controller');

		$this->db->where('id', $card_id);
		$this->db->update('cards', ['holder_id' => $person_id]);

		return $this->db->affected_rows();

		//TODO добавление карты в контроллера
		//$this->controller->add_cards($controller_id, $card);
	}

	/**
	 * Установить время последней связи с картой
	 *
	 * @param int $card_id       ID карты
	 * @param int $controller_id ID контроллера
	 */
	public function set_last_conn($card_id, $controller_id)
	{
		$data = [
			'last_conn' => now('Asia/Yekaterinburg'),
			'controller_id' => $controller_id
		];
		$this->db->where('id', $card_id);
		$this->db->update('cards', $data);
	}

	/**
	 * Удаление карты
	 *
	 * @param int $card_id ID карты
	 * @return int
	 */
	public function delete($card_id)
	{
		$this->load->model('ac/controller_model', 'controller');

		$this->db->where('id', $card_id);
		$this->db->update('cards', ['holder_id' => -1]);

		return $this->db->affected_rows();

		//TODO удаление карты из контроллера
		//$this->controller->delete_cards($controller_id, $card);
	}
}

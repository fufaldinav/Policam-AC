<?php
/**
 * Name:   Policam AC Card Model
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
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
* Class Card Model
* @property Ctrl_model $ctrl
*/
class Card_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о карте по ID
	*
	* @param int $card_id ID карты
	* @return object|null Карта или NULL - отсутствует
	*/
	public function get(int $card_id)
	{
		$query = $this->db
			->where('id', $card_id)
			->get('cards');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о карте по коду
	*
	* @param string $code Код карты
	* @return object|null Карта или NULL - отсутствует
	*/
	public function get_by_code(string $code)
	{
		$query = $this->db
			->where('wiegand', $code)
			->get('cards');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение списка карт по человеку
	*
	* @param int $holder_id ID человека, по-умолчанию -1 (список всех неизвестных карт)
	* @return mixed[] Массив с картами или NULL - отсутствует
	*/
	public function get_by_holder(int $holder_id = -1)
	{
		$query = $this->db
			->where('holder_id', $holder_id)
			->get('cards');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Получение информации о всех картах
	*
	* @param int|null $ctrl_id ID контроллера
	* @return object[]|null Массив с картами или NULL - отсутствует
	*/
	public function get_all(int $ctrl_id = null)
	{
		if (isset($ctrl_id)) {
			$this->db->where('controller_id', $ctrl_id);
		}
		$query = $this->db->get('cards');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Закрепление карты за человеком
	*
	* @param int $card_id   ID карты
	* @param int $person_id ID человека
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function set_holder(int $card_id, int $person_id): bool
	{
		$this->load->model('ac/ctrl_model', 'ctrl');

		$this->db
			->where('id', $card_id)
			->update('cards', ['holder_id' => $person_id]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Установить время последней связи с картой
	 *
	 * @param int $card_id ID карты
	 * @param int $ctrl_id ID контроллера
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function set_last_conn(int $card_id, int $ctrl_id): bool
	{
		$data = [
			'last_conn' => now('Asia/Yekaterinburg'),
			'controller_id' => $ctrl_id
		];
		$this->db
			->where('id', $card_id)
			->update('cards', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Добавление новой карты
	*
	* @param object $card Карта
	* @return int ID новой карты
	*/
	public function add($card): int
	{
		$this->db->insert('cards', $this->set($card));

		return $this->db->insert_id();
	}

	/**
	* Обновление информации о карте
	*
	* @param object $card Карта
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function update($card): bool
	{
		$this->db
			->where('id', $card->id)
			->update('cards', $this->set($card));

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Удаление карты
	 *
	 * @param int $card_id ID карты
	 * @return bool TRUE - успешно, FALSE - ошибка
	 */
	public function delete(int $card_id): bool
	{
		$this->load->model('ac/ctrl_model', 'ctrl');

		$this->db
			->where('id', $card_id)
			->update('cards', ['holder_id' => -1]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Установить информацию о карте
	*
	* @param object $card Карта
	* @return mixed[] Массив с параметрами карты
	*/
	public function set($card): array
	{
		$data = [
			'wiegand' => $card->wiegand,
			'last_conn' => $card->last_conn,
			'controller_id' => $card->controller_id,
			'holder_id' => $card->holder_id
		];

		return $data;
	}
}

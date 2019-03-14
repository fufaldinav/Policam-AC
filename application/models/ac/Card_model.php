<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
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
 * Class Card Model
 */
class Card_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
	}

	/**
	 * Получает карту по ID
	 *
	 * @param int $card_id ID карты
	 *
	 * @return object|null Карта или NULL, если не найдена
	 */
	public function get(int $card_id): ?object
	{
		$query = $this->db
			->where('id', $card_id)
			->get('cards');

		return $query->row();
	}

	/**
	 * Получает карту по коду карты
	 *
	 * @param string $code Код карты
	 *
	 * @return object|null Карта или NULL, если не найдена
	 */
	public function get_by_code(string $code): ?object
	{
		$query = $this->db
			->where('wiegand', $code)
			->get('cards');

		return $query->row();
	}

	/**
	 * Получает список карт человека или никому не принадлежащие карты
	 *
	 * @param int $person_id ID человека, по-умолчанию 0 - никому не принадлежащие карты
	 *
	 * @return object[] Массив с картами или пустой массив
	 */
	public function get_by_person(int $person_id = 0): array
	{
		$query = $this->db
			->where('person_id', $person_id)
			->get('cards');

		return $query->result();
	}

	/**
	 * Получает список всех карт
	 *
	 * @param int|null $ctrl_id ID контроллера
	 *
	 * @return object[] Массив с картами или пустой массив
	 */
	public function get_all(int $ctrl_id = null): array
	{
		if (isset($ctrl_id)) {
			$this->db->where('controller_id', $ctrl_id);
		}
		$query = $this->db->get('cards');

		return $query->result();
	}

	/**
	 * Закрепляет карту за человеком
	 *
	 * @param int $card_id   ID карты
	 * @param int $person_id ID человека
	 *
	 * @return int Количество успешных записей
	*/
	public function set_holder(int $card_id, int $person_id): int
	{
		$this->db
			->where('id', $card_id)
			->update('cards', ['person_id' => $person_id]);

		return $this->db->affected_rows();
	}

	/**
	 * Записывает время последнего считывания карты на контроллере
	 *
	 * @param int $card_id ID карты
	 * @param int $ctrl_id ID контроллера
	 *
	 * @return int Количество успешных записей
	 */
	public function set_last_conn(int $card_id, int $ctrl_id): int
	{
		$data = [
			'last_conn' => now('Asia/Yekaterinburg'),
			'controller_id' => $ctrl_id
		];
		$this->db
			->where('id', $card_id)
			->update('cards', $data);

		return $this->db->affected_rows();
	}

	/**
	 * Добавляет новую карту
	 *
	 * @param object $card Карта
	 *
	 * @return int ID новой карты
	 */
	public function add(object $card): int
	{
		$this->db->insert('cards', $this->_set($card));

		return $this->db->insert_id();
	}

	/**
	 * Обновляет информацию о карте
	 *
	 * @param object $card Карта
	 *
	 * @return int Количество успешных записей
	 */
	public function update(object $card): int
	{
		$this->db
			->where('id', $card->id)
			->update('cards', $this->_set($card));

		return $this->db->affected_rows();
	}

	/**
	 * Удаляет карту
	 *
	 * @param int $card_id ID карты
	 *
	 * @return int Количество удаленных записей
	 */
	public function delete(int $card_id): int
	{
		$this->db->delete('events', ['card_id' => $card_id]);
		$this->db->delete('cards', ['id' => $card_id]);

		return $this->db->affected_rows();
	}

	/**
	 * Получает объект и возвращает массив для записи
	 *
	 * @param object $card Карта
	 *
	 * @return mixed[] Массив с параметрами карты
	 */
	private function _set(object $card): array
	{
		$data = [
			'wiegand' => $card->wiegand,
			'last_conn' => $card->last_conn,
			'controller_id' => $card->controller_id,
			'person_id' => $card->person_id
		];

		return $data;
	}
}

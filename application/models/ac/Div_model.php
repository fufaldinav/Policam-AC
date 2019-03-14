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
 * Class Div Model
 */
class Div_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
	}

	/**
	 * Получает подразделениe
	 *
	 * @param int $div_id ID подразделения
	 *
	 * @return object|null Подразделение или NULL, если не найдено
	 */
	public function get(int $div_id): ?object
	{
		$query = $this->db
			->where('id', $div_id)
			->get('divisions');

		return $query->row();
	}

	/**
	 * Получает все подразделения по организации и/или типу
	 *
	 * @param int|null $org_id ID организации
	 * @param int|null $type   Тип организации, по-умолчанию NULL - классы любого типа
	 *
	 * @return object[] Массив с подразделениями или пустой массив
	 */
	public function get_all(int $org_id = null, int $type = null): array
	{
		if (isset($org_id)) {
			$this->db->where('org_id', $org_id);
		}
		if (isset($type)) {
			$this->db->where('type', $type);
		}
		$query = $this->db
			->order_by('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
			->get('divisions');

		return $query->result();
	}

	/**
	 * Добавляет новое подразделение
	 *
	 * @param object $div Подразделение
	 *
	 * @return int ID нового подразделения
	 */
	public function add(object $div): int
	{
		$this->db->insert('divisions', $this->_set($div));

		return $this->db->insert_id();
	}

	/**
	 * Обновляет информацию о подразделении
	 *
	 * @param object $div Подразделение
	 *
	 * @return int Количество успешных записей
	 */
	public function update(object $div): int
	{
		$this->db
			->where('id', $div->id)
			->update('divisions', $this->_set($div));

		return $this->db->affected_rows();
	}

	/**
	 * Удаляет подразделение
	 *
	 * @param int $div_id ID подразделения
	 *
	 * @return int Количество успешных удалений
	 */
	public function delete(int $div_id): int
	{
		$this->db->delete('persons_divisions', ['div_id' => $div_id]);
		$this->db->delete('divisions', ['id' => $div_id]);

		return $this->db->affected_rows();
	}

	/**
	 * Добавляет людей в подразделение
	 *
	 * @param int[] $persons_ids Список ID людей
	 * @param int   $div_id      ID подразделения
	 *
	 * @return int Количество успешных записей
	 */
	public function add_persons(array $persons_ids, int $div_id): int
	{
		$data = [];
		foreach ($persons_ids as $person_id) {
			$data[] = [
				'div_id' => $div_id,
				'person_id' => $person_id
			];
		}
		$this->db->insert_batch('persons_divisions', $data);

		return $this->db->affected_rows();
	}

	/**
	 * Удаляет людей из подразделения или всех подразделений
	 *
	 * @param int[]    $persons_ids Список ID людей
	 * @param int|null $div_id      ID подразделения, по-умолчанию NULL - из всех подразделений
	 *
	 * @return int Количество успешных удалений
	 */
	public function delete_persons(array $persons_ids, int $div_id = null): int
	{
		if (isset($div_id)) {
			$this->db->where('div_id', $div_id);
		}
		$this->db
			->where_in('person_id', $persons_ids)
			->delete('persons_divisions');

		return $this->db->affected_rows();
	}

	/**
	 * Получает объект и возвращает массив для записи
	 *
	 * @param object $div Подразделение
	 *
	 * @return mixed[] Массив с параметрами контроллера
	 */
	private function _set($div): array
	{
		$data = [
			'name' => $div->name,
			'org_id' => $div->org_id,
			'type' => $div->type ?? 1
		];

		return $data;
	}
}

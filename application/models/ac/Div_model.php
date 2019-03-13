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
	 * Получает все подразделения по организации
	 *
	 * @param int|null $org_id ID организации
	 *
	 * @return object[] Массив с подразделениями или пустой массив
	 */
	public function get_all(int $org_id = null): array
	{
		if (isset($org_id)) {
			$this->db->where('org_id', $org_id);
		}
		$query = $this->db
			->order_by('number ASC', 'letter ASC')
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
		$this->db->delete('divisions', ['id' => $div_id]);

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
			'number' => $div->number,
			'letter' => $div->letter,
			'org_id' => $div->org_id
		];

		return $data;
	}
}

<?php
/**
 * Name:   Policam AC Div Model
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
* Class Div Model
*/
class Div_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о подразделении
	*
	* @param int $div_id ID подразделения
	* @return mixed[]
	*/
	public function get($div_id)
	{
		$this->db->where('id', $div_id);
		$query = $this->db->get('divisions');

		return $query->row();
	}

	/**
	* Получение информации о всех подразделениях организации
	*
	* @param int|null $org_id ID организации, по-умолчанию все подразделения
	* @return mixed[]
	*/
	public function get_all($org_id = null)
	{
		if ($org_id !== null) {
			$this->db->where('org_id', $org_id);
		}
		$this->db->order_by('number', 'ASC');
		$this->db->order_by('letter', 'ASC');
		$query = $this->db->get('divisions');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Добавление нового подразделения
	*
	* @param object $div Подразделение
	* @return int
	*/
	public function add($div)
	{
		$this->db->insert('divisions', $this->set($div));
		$div_id = $this->db->insert_id();

		return $div_id;
	}

	/**
	* Обновление информации о подразделении
	*
	* @param object $div Подразделение
	* @return int
	*/
	public function update($div)
	{
		$this->db->where('id', $div->id);
		$this->db->update('divisions', $this->set($div));

		return $this->db->affected_rows();
	}

	/**
	* Удаление подразделения
	*
	* @param int $div_id ID подразделения
	* @return int
	*/
	public function delete($div_id)
	{
		$this->db->delete('divisions', ['id' => $div_id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию о подразделении
	*
	* @param object $div Подразделение
	* @return mixed[]
	*/
	public function set($div)
	{
		$data = [
			'number' => $div->number,
			'letter' => $div->letter,
			'org_id' => $div->org_id
		];

		return $data;
	}
}

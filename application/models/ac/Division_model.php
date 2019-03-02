<?php
/**
 * Name:    Division Model
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
* Class Division Model
*/
class Division_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о подразделении
	*
	* @param   int      $div_id
	* @return  mixed[]
	*/
	public function get($div_id)
	{
		$this->db->where('id', $div_id);
		$query = $this->db->get('divisions');

		return $query->row();
	}

	/**
	* Получение информации о всех подразделениях
	*
	* @param   int      $org_id
	* @return  mixed[]
	*/
	public function get_all($org_id = null)
	{
		if ($org_id !== null) {
			$this->db->where('org_id', $org_id);
		}
		$query = $this->db->get('divisions');

		return $query->result();
	}

	/**
	* Добавление нового подразделения
	*
	* @param   object  $div
	* @return  int
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
	* @param   object  $div
	* @return  int
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
	* @param   int  $div_id
	* @return  int
	*/
	public function delete($div_id)
	{
		$this->db->delete('divisions', ['id' => $div_id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию о подразделении
	*
	* @param   object   $div
	* @return  mixed[]
	*/
	public function set($div)
	{
		$data = [

		];

		return $data;
	}
}

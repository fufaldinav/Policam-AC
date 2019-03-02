<?php
/**
 * Name:    Organization Model
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
* Class Organization Model
*/
class Organization_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о организации
	*
	* @param   int      $org_id
	* @return  mixed[]
	*/
	public function get($org_id)
	{
		$this->db->where('id', $org_id);
		$query = $this->db->get('organizations');

		return $query->row();
	}

	/**
	* Получение информации о всех организациях
	*
	* @param   int      $user_id
	* @return  mixed[]
	*/
	public function get_all($user_id = null)
	{
		if ($user_id !== null) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->join('organizations', 'organizations.id = organizations_users.org_id', 'left');
		$this->db->order_by('number', 'ASC');
		$query = $this->db->get('organizations_users');

		return $query->result();
	}

	/**
	* Добавление новой организации
	*
	* @param   object  $org
	* @return  int
	*/
	public function add($org)
	{
		$this->db->insert('organizations', $this->set($org));

		return $this->db->insert_id();
	}

	/**
	* Обновление информации об организациях
	*
	* @param   object  $org
	* @return  int
	*/
	public function update($org)
	{
		$this->db->where('id', $id);
		$this->db->update('organizations', $this->set($org));

		return $this->db->affected_rows();
	}

	/**
	* Удаление организации
	*
	* @param   int  $org_id
	* @return  int
	*/
	public function delete($org_id)
	{
		$this->db->delete('organizations', ['id' => $org_id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию об организации
	*
	* @param   object   $org
	* @return  mixed[]
	*/
	public function set($org)
	{
		$this->data = [

		];

		return $data;
	}
}

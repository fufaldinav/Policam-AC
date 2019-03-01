<?php
/**
 * Name:    Organization Model
 * Author:  Artem Fufaldin
 *          artem.fufaldin@gmail.com
 * @m2jest1c
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
	/**
	* @var int
	*/
	public $id;

	/**
	* @var mixed[]
	*/
	private $data;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о организации
	*
	* @param   int      $id
	* @return  mixed[]
	*/
	public function get($id = null)
	{
		if ($id === null) {
			$id = $this->id;
		}

		$this->db->where('id', $id);
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
		$query = $this->db->get('organizations');

		return $query->result();
	}

	/**
	* Добавление новой организации
	*
	* @return  int
	*/
	public function add()
	{
		$this->db->insert('organizations', $this->data);
		$org_id = $this->db->insert_id();

		return $org_id;
	}

	/**
	* Обновление информации об организациях
	*
	* @param   int  $id
	* @return  int
	*/
	public function update($id)
	{
		$this->db->where('id', $id);
		$this->db->update('organizations', $this->data);

		return $this->db->affected_rows();
	}

	/**
	* Удаление организации
	*
	* @param   int  $id
	* @return  int
	*/
	public function delete($id)
	{
		$this->db->delete('organizations', ['id' => $id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию об организации
	*
	* @param  object  $org
	*/
	public function set($org)
	{
		$this->data = [

		];
	}
}

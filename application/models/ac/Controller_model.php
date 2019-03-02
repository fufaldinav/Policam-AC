<?php
/**
 * Name:    Controller Model
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
	* @param   int      $controller_id
	* @return  mixed[]
	*/
	public function get($controller_id)
	{
		$this->db->where('id', $controller_id);
		$query = $this->db->get('controllers');

		return $query->row();
	}

	/**
	* Получение информации о всех контроллерах
	*
	* @param   int      $org_id
	* @return  mixed[]
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
	* @param   object  $controller
	* @return  int
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
	* @param   object  $controller
	* @return  int
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
	* @param   int  $controller_id
	* @return  int
	*/
	public function delete($controller_id)
	{
		$this->db->delete('controllers', ['id' => $controller_id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию о контроллере
	*
	* @param   object   $controller
	* @return  mixed[]
	*/
	public function set($controller)
	{
		$this->data = [

		];

		return $data;
	}
}

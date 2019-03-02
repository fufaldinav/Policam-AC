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
	* Получение информации о контроллере
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
			$this->db->where('user_id', $org_id);
		}
		$query = $this->db->get('controllers');

		return $query->result();
	}

	/**
	* Добавление нового контроллера
	*
	* @return  int
	*/
	public function add()
	{
		$this->db->insert('controllers', $this->data);
		$org_id = $this->db->insert_id();

		return $controller_id;
	}

	/**
	* Обновление информации о контроллере
	*
	* @param   int  $id
	* @return  int
	*/
	public function update($id)
	{
		$this->db->where('id', $id);
		$this->db->update('controllers', $this->data);

		return $this->db->affected_rows();
	}

	/**
	* Удаление контроллера
	*
	* @param   int  $id
	* @return  int
	*/
	public function delete($id)
	{
		$this->db->delete('controllers', ['id' => $id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию о контроллере
	*
	* @param  object  $org
	*/
	public function set($org)
	{
		$this->data = [

		];
	}
}

<?php
/**
 * Name:    Card Model
 * Author:  Artem Fufaldin
 *          artem.fufaldin@gmail.com
 * @m2jest1c
 *
 * Created:  01.12.2018
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
* Class Ac Model
*/
class Card_model extends CI_Model
{
	/**
	* @var int
	*/
	public $id;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о карте
	*
	* @param   int      $id
	* @return  object
	*/
	public function get($id = null)
	{
		if ($id === null) {
			$id = $this->id;
		}

		$this->db->where('id', $id);
		$query = $this->db->get('cards');

		return $query->row();
	}

	/**
	* Получение информации о всех картах
	*
	* @param   int      $controller_id
	* @return  mixed[]
	*/
	public function get_all($controller_id = null)
	{
		if (isset($controller_id)) {
			$this->db->where('controller_id', $controller_id);
		}
		$query = $this->db->get('cards');

		return $query->result();
	}

	/**
	* Установить владельца карты
	*
	* @param   int  $person_id
	* @return  int
	*/
	public function set_holder($person_id)
	{
		$this->db->where('id', $this->id);
		$this->db->update('cards', ['holder_id' => $person_id]);

		return $this->db->affected_rows();

		//TODO запись карт в контроллер
		//$this->ac_model->add_card($this->id);
	}
}

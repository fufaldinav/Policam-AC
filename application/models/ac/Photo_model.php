<?php
/**
 * Name:    Photo Model
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
class Photo_model extends CI_Model
{
	/**
	* @var int
	*/
	public $id;

	/**
	* @var string
	*/
	public $hash;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о фотографии
	*
	* @return  mixed[]
	*/
	public function get()
	{
		$this->db->select('*');
		if (isset($this->id)) {
			$this->db->where('id', $this->id);
		}
		if (isset($this->hash)) {
			$this->db->where('hash', $this->hash);
		}
		$query = $this->db->get('photo');

		return $query->row();
	}

	/**
	* Установить владельца фотографии
	*
	* @param   int  $person_id
	* @return  int
	*/
	public function set_person($person_id)
	{
		$this->db->where('id', $this->id);
		$this->db->update('photo', ['person_id' => $person_id]);

		return $this->db->affected_rows();
	}
}

<?php
/**
 * Name:    Person Model
 * Author:  Artem Fufaldin
 *          artem.fufaldin@gmail.com
 *
 * Created:  01.03.2019
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
class Person_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о человеке
	*
	* @param   int      $person_id
	* @return  mixed[]
	*/
	public function get($person_id)
	{
		$this->db->where('id', $person_id);
		$query = $this->db->get('persons');

		return $query->row();
	}

	/**
	* Получение информации о всех людях
	*
	* @param   int      $div_id
	* @return  mixed[]
	*/
	public function get_all($div_id = null)
	{
		if ($div_id !== null) {
			$this->db->where('div_id', $div_id);
		}
		$query = $this->db->get('persons');

		return $query->result();
	}

	/**
	* Добавление нового человека
	*
	* @param   object  $person
	* @return  int
	*/
	public function add($person)
	{
		$this->db->insert('persons', $this->set($person));
		$person_id = $this->db->insert_id();

		return $person_id;
	}

	/**
	* Обновление информации о человеке
	*
	* @param   object  $person
	* @return  int
	*/
	public function update($person)
	{
		$this->db->where('id', $person->id);
		$this->db->update('persons', $this->set($person));

		return $this->db->affected_rows();
	}

	/**
	* Удаление человека
	*
	* @param   int  $person_id
	* @return  int
	*/
	public function delete($person_id)
	{
		$this->db->delete('persons', ['id' => $person_id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию о человеке
	*
	* @param   object   $person
	* @return  mixed[]
	*/
	public function set($person)
	{
		$data = [
			'div_id' => $person->div,
			'f' => $person->f,
			'i' => $person->i,
			'o' => $person->o,
			'birthday' => $person->birthday,
			'address' => $person->address,
			'phone' => $person->phone,
			'photo_id' => $person->photo
		];

		return $data;
	}

	/**
	* Удалить инфомации о фотографии у человека
	*
	* @param   int  $person_id
	* @return  int
	*/
	public function delete_photo($person_id)
	{
		$this->db->where('id', $person_id);
		$this->db->update('persons', ['photo_id' => null]);

		return $this->db->affected_rows();
	}
}

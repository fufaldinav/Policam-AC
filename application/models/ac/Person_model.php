<?php
/**
 * Name:    Person Model
 * Author:  Artem Fufaldin
 *          artem.fufaldin@gmail.com
 * @m2jest1c
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
	* Получение информации о человеке
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
	* @return  int
	*/
	public function add()
	{
		$this->db->insert('persons', $this->data);
		$person_id = $this->db->insert_id();

		return $person_id;
	}

	/**
	* Обновление информации о человеке
	*
	* @param   int  $id
	* @return  int
	*/
	public function update($id)
	{
		$this->db->where('id', $id);
		$this->db->update('persons', $this->data);

		return $this->db->affected_rows();
	}

	/**
	* Удаление человека
	*
	* @param   int  $id
	* @return  int
	*/
	public function delete($id)
	{
		$this->db->delete('persons', ['id' => $id]);

		return $this->db->affected_rows();
	}

	/**
	* Установить информацию о человеке
	*
	* @param  object  $person
	*/
	public function set($person)
	{
		$this->data = [
			'div_id' => $person->div,
			'f' => $person->f,
			'i' => $person->i,
			'o' => $person->o,
			'birthday' => $person->birthday,
			'address' => $person->address,
			'phone' => $person->phone,
			'photo_id' => $person->photo
		];
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

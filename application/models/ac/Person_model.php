<?php
/**
 * Name:    Person Model
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
class Person_model extends CI_Model
{
	/**
	* @var int
	*/
	public $id;

	/**
	* @var mixed[]
	*/
	private $person;

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
	* Сохранение информации о человеке
	*
	* @param   object  $person
	* @return  int
	*/
	public function add($person)
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

		$this->db->insert('persons', $data);

		$person_id = $this->db->insert_id();

		if ($person->photo !== null) {
			$this->load->model('ac/photo_model', 'photo');
			
			$this->photo->id = $person->photo;
			$this->photo->set_person($person_id);
		}

		if ($person->card > 0) {
			$this->load->model('ac/card_model', 'card');

			$this->card->id = $person->card;
			$this->card->set_holder($person_id);
		}

		return $person_id;
	}

	/**
	* Сохранение информации о человеке
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

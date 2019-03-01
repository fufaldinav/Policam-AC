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

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Получение информации о человеке
	*
	* @return  mixed[]
	*/
	public function get()
	{
		$this->db->select('*');
		$this->db->where('id', $this->id);
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
		$this->db->select('*');
		$this->db->where('div_id', $div_id);
		$query = $this->db->get('persons');

		return $query->result();
	}

	/**
	* Сохранение информации о человеке
	*
	* @param   mixed[]  $person
	* @return  int
	*/
	public function save($person)
	{
		if (isset($person['photo'])) {
			$this->load->model('ac/photo_model', 'photo');

			$this->photo->hash = $person['photo'];
			$photo_id = $this->photo->get()->id;
		}

		$data = [
			'div_id' => $person['div'],
			'f' => $person['f'],
			'i' => $person['i'],
			'o' => (isset($person['o'])) ? $person['o'] : null,
			'birthday' => $person['birthday'],
			'address' => (isset($person['address'])) ? $person['address'] : null,
			'phone' => (isset($person['phone'])) ? $person['phone'] : null,
			'photo_id' => (isset($photo_id)) ? $photo_id : null
		];

		$this->db->insert('persons', $data);

		$person_id = $this->db->insert_id();

		if (isset($photo_id)) {
			$this->photo->id = $photo_id;
			$this->photo->set_person($person_id);
		}

		if ($person['card'] > 0) {
			$this->load->model('ac/card_model', 'card');

			$this->card->id = $person['card'];
			$this->card->set_holder($person_id);
		}

		return $person_id;
	}
}

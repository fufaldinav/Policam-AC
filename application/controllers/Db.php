<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Db
 * @property  Ac_model  $ac
 */
class Db extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('language');

		$this->load->model('ac_model');
		$this->lang->load('ac');

		if ($this->ion_auth->logged_in()) {
			$this->user_id = $this->ion_auth->user()->row()->id;
		}
	}

	/**
	 * Сохранение нового человека
	 */
	public function save_person()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->load->model('ac/person_model', 'person');

		$person = json_decode($this->input->post('person'), true);

		$person_id = $this->person->save($person);

		echo $person_id;
	}

	/**
	 * Обновление информации о человеке
	 */
	public function update_person()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$person = json_decode($this->input->post('person'), true);

		if (isset($person['photo'])) {
			$this->db->select('id');
			$this->db->where('hash', $person['photo']);
			$query = $this->db->get('photo');
			$photo_id = $query->row()->id;
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

		$this->db->where('id', $person['id']);
		$this->db->update('persons', $data);

		if (isset($photo_id)) {
			$this->db->where('id', $photo_id);
			$this->db->update('photo', ['person_id' => $person['id']]);
		}

		if ($person['card'] > 0) {
			$this->db->where('id', $person['card']);
			$this->db->update('cards', ['holder_id' => $person['id']]);

			$this->ac_model->add_card($person['card']);
		}

		echo $person['id'];
	}

	/**
	 * Удаление человека
	 */
	public function delete_person()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$person_id = $this->input->post('person_id');

		if (!isset($person_id)) {
			return null;
		}

		$cards = $this->ac_model->get_cards($person_id);

		if ($cards) {
			foreach ($cards as $card) {
				$this->ac_model->delete_card($card->id);
			}
		}

		$this->ac_model->delete_photo($person_id);

		$this->db->delete('persons', ['id' => $person_id]);

		if ($this->db->affected_rows() > 0) {
			echo $person_id;
		} else {
			return null;
		}
	}

	/**
	 * Добавление карты
	 */
	public function add_card()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$card_id = $this->input->post('card_id');
		$person_id = $this->input->post('person_id');


		if (isset($card_id) && isset($person_id)) {
			$this->db->where('id', $card_id);
			$this->db->update('cards', ['holder_id' => $person_id]);

			if ($this->ac_model->add_card($card_id)) {
				echo 'ok';
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	/**
	 * Удаление карты
	 */
	public function delete_card()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$card_id = $this->input->post('card_id');

		if (!isset($card_id)) {
			return null;
		}

		if ($this->ac_model->delete_card($card_id)) {
			echo 'ok';
		}
	}

	/**
	 * Сохранение нового подразделения
	 */
	public function save_div()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$number = $this->input->post('number');
		$letter = $this->input->post('letter');
		$org_id = $this->input->post('org_id');

		if (!isset($number) || !isset($letter) || !isset($org_id)) {
			return null;
		}

		$data = [
			'number' => $number,
			'letter' => $letter,
			'org_id' => $org_id
		];

		$this->db->insert('divisions', $data);

		if ($this->db->affected_rows()) {
			echo json_encode($data);
		}
	}

	/**
	 * Удаление подразделения
	 */
	public function delete_div()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$div_id = $this->input->post('div_id');

		if (!isset($div_id)) {
			return null;
		}

		$this->db->where('id', $div_id);
		$this->db->delete('divisions');

		if ($this->db->affected_rows()) {
			$this->db->where('div_id', $div_id);
			$this->db->update('persons', ['div_id' => null]);
			echo 'ok';
		}
	}

	/**
	 * Получение информации о подразделении
	 */
	public function get_divisions()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$org_id = $this->ion_auth->user()->row()->org_id;

		$divisions = $this->ac_model->get_divisions_by_org($org_id);

		echo json_encode($divisions);
	}

	/**
	 * Получение информации о людях в подразделении
	 *
	 * @param  int  $div_id
	 */
	public function get_persons($div_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$persons = $this->ac_model->get_persons_by_div($div_id, $full_info = false);

		echo json_encode($persons);
	}

	/**
	 * Получение информации о человеке
	 */
	public function get_person()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$card_id = $this->input->post('card_id');
		$person_id = $this->input->post('person_id');

		if (!isset($person_id)) {
			$person = $this->ac_model->get_person_by_card($card_id);
			$div = $this->ac_model->get_div_by_id($person->div);
			$person->div = $div->number . ' "' . $div->letter . '"';
		} else {
			$person = $this->ac_model->get_person($person_id);
		}

		if ($person) {
			echo json_encode($person);
		} else {
			return null;
		}
	}

	/**
	 * Получение информации о картах
	 */
	public function get_cards()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->db->select('id, wiegand');
		$this->db->where('holder_id', -1);
		$query = $this->db->get('cards');

		echo json_encode($query->result());
	}

	/**
	 * Получение информации о картах конкретного человека
	 */
	public function get_cards_by_person()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$holder_id = $this->input->post('holder_id');

		if (!isset($holder_id)) {
			return null;
		}

		$cards = $this->ac_model->get_cards($holder_id);

		if ($cards) {
			echo json_encode($cards);
		}
	}
}

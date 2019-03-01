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
		$this->load->model('ac/card_model', 'card');
		$this->load->model('ac/person_model', 'person');
		$this->load->model('ac/photo_model', 'photo');
		$this->lang->load('ac');

		if ($this->ion_auth->logged_in()) {
			$this->user_id = $this->ion_auth->user()->row()->id;
		}
	}

	/**
	 * Сохранение нового человека
	 */
	public function add_person()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$person = json_decode($this->input->post('person'));

		$this->person->set($person);
		$person_id = $this->person->add();

		if ($person->photo !== null) {
			$this->photo->id = $person->photo;
			$this->photo->set_person($person_id);
		}

		if ($person->card > 0) {
			$this->card->id = $person->card;
			$this->card->add($person_id);
		}

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

		$person = json_decode($this->input->post('person'));

		$this->person->set($person);
		$count = $this->person->update($person->id);

		if ($person->photo !== null) {
			$this->photo->id = $person->photo;
			$this->photo->set_person($person->id);
		}

		if ($person->card > 0) {
			$this->card->id = $person->card;
			$this->card->add($person->id);
		}

		echo $count;
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

		$cards = $this->card->get_by_holder($person_id);
		if ($cards) {
			foreach ($cards as $card) {
				$this->card->delete($card->id);
			}
		}

		$photo = $this->photo->get_by_person($person_id);
		if ($photo) {
			$this->photo->delete($photo->id);
		}

		echo $this->person->delete($person_id);
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

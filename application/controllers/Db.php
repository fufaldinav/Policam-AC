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
	 * Добавление нового человека
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

		$person_id = $this->person->add($person);

		if ($person->photo !== null) {
			$this->photo->set_person($person->photo, $person_id);
		}

		if ($person->card > 0) {
			$this->card->set_holder($person->card, $person_id);
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

		$count = $this->person->update($person);

		if ($person->photo !== null) {
			$this->photo->set_person($person->photo, $person->id);
		}

		if ($person->card > 0) {
			$this->card->set_holder($person->card, $person->id);
		}

		echo $count;
	}

	/**
	 * Удаление человека
	 *
	 * @param  int  $person_id
	 */
	public function delete_person($person_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

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
	 *
	 * @param  int  $card_id
	 * @param  int  $person_id
	 */
	public function add_card($card_id, $person_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		if ($this->card->set_holder($card_id, $person_id) > 0) {
			echo 'ok';
		}
	}

	/**
	 * Удаление карты
	 *
	 * @param  int  $card_id
	 */
	public function delete_card($card_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		if ($this->card->delete($card_id)) {
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
	 *
	 * @param  int  $div_id
	 */
	public function delete_div($div_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
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

		$persons = $this->person->get_all($div_id);

		echo json_encode($persons);
	}

	/**
	 * Получение информации о человеке
	 *
	 * @param  int  $person_id
	 */
	public function get_person($person_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$person = $this->person->get($person_id);

		echo json_encode($person);
	}

	/**
	 * Получение информации о человеке
	 *
	 * @param  int  $card_id
	 */
	public function get_person_by_card($card_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$card = $this->card->get($card_id);

		$person = $this->person->get($card->holder_id);

		echo json_encode($person);
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
	 *
	 * @param  int  $holder_id
	 */
	public function get_cards_by_person($holder_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$cards = $this->ac_model->get_cards($holder_id);

		if ($cards) {
			echo json_encode($cards);
		}
	}
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Persons
 * @property  Card_model    $card
 * @property  Person_model  $person
 * @property  Photo_model   $photo
 */
class Persons extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('language');

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
	public function add()
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
	public function update()
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
	public function delete($person_id)
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
	 * Получение информации о человеке
	 *
	 * @param  int  $person_id
	 */
	public function get($person_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$person = $this->person->get($person_id);

		echo json_encode($person);
	}

	/**
	 * Получение информации о людях в подразделении
	 *
	 * @param  int  $div_id
	 */
	public function get_all($div_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$persons = $this->person->get_all($div_id);

		echo json_encode($persons);
	}
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Persons
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Org_model $org
 * @property Person_model $person
 * @property Photo_model $photo
 */
class Persons extends CI_Controller
{
	/**
	* @var int $user_id
	*/
	private $user_id;

	/**
	* @var mixed[] $orgs
	*/
	private $orgs;

	/**
	* @var mixed[] $first_org
	*/
	private $first_org;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('ac/card_model', 'card');
		$this->load->model('ac/ctrl_model', 'ctrl');
		$this->load->model('ac/org_model', 'org');
		$this->load->model('ac/person_model', 'person');
		$this->load->model('ac/photo_model', 'photo');

		$this->user_id = $this->ion_auth->user()->row()->id; //TODO
		$this->orgs = $this->org->get_all($this->user_id); //TODO
		$this->first_org = array_shift($this->orgs); //TODO
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

			$card = $this->card->get($person->card);

			$ctrls = $this->ctrl->get_all($this->first_org->id);
			foreach ($ctrls as $ctrl) {
				$this->ctrl->add_cards($ctrl->id, $card->wiegand);
			}
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
			$count += $this->photo->set_person($person->photo, $person->id);
		}

		if ($person->card > 0) {
			$count += $this->card->set_holder($person->card, $person->id);

			$card = $this->card->get($person->card);

			$ctrls = $this->ctrl->get_all($this->first_org->id);
			foreach ($ctrls as $ctrl) {
				$this->ctrl->add_cards($ctrl->id, $card->wiegand);
			}
		}

		echo $count;
	}

	/**
	 * Удаление человека
	 *
	 * @param int $person_id ID человека
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
		if ($cards !== null) {

			$ctrls = $this->ctrl->get_all($this->first_org->id);

			foreach ($cards as $card) {
				$this->card->delete($card->id);

				foreach ($ctrls as $ctrl) {
					$this->ctrl->delete_cards($ctrl->id, $card->wiegand);
				}
			}
		}

		$photo = $this->photo->get_by_person($person_id);
		if ($photo !== null) {
			$this->photo->delete($photo->id);
		}

		echo $this->person->delete($person_id);
	}

	/**
	 * Получение информации о человеке
	 *
	 * @param int $person_id
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
	 * Получение информации о человеке
	 *
	 * @param int $card_id
	 */
	public function get_by_card($card_id)
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
	 * Получение информации о людях в подразделении
	 *
	 * @param int $div_id
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

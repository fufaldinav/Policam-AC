<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Persons
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 * @property Photo_model $photo
 * @property Task_model $task
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

		$this->load->library('ion_auth');

		if (! $this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$this->load->model('ac/card_model', 'card');
		$this->load->model('ac/ctrl_model', 'ctrl');
		$this->load->model('ac/div_model', 'div');
		$this->load->model('ac/org_model', 'org');
		$this->load->model('ac/person_model', 'person');
		$this->load->model('ac/photo_model', 'photo');
		$this->load->model('ac/task_model', 'task');

		$this->user_id = $this->ion_auth->user()->row()->id;
		$this->orgs = $this->org->get_all($this->user_id); //TODO
		$this->first_org = array_shift($this->orgs); //TODO
	}

	/**
	 * Добавляет нового человека
	 */
	public function add()
	{
		if (! $this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$divs = json_decode($this->input->post('divs'));
		$person = json_decode($this->input->post('person'));

		$person_id = $this->person->add($person);

		if (count($divs) > 0) {
			foreach ($divs as $div) {
				$this->div->add_persons([$person_id], $div);
			}
		} else {
			$divs = $this->div->get_all($this->first_org->id, 0);
			$div = array_shift($divs);
			$this->div->add_persons([$person_id], $div->id);
		}

		if (isset($person->photo)) {
			$this->photo->set_person($person->photo, $person_id);
		}

		if ($person->card > 0) {
			$this->card->set_holder($person->card, $person_id);

			$card = $this->card->get($person->card);

			$ctrls = $this->ctrl->get_all($this->first_org->id);
			foreach ($ctrls as $ctrl) {
				$this->task->add_cards($ctrl->id, [$card->wiegand]);
			}
		}

		echo $person_id;
	}

	/**
	 * Обновляет информацию о человеке
	 */
	public function update()
	{
		if (! $this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$person = json_decode($this->input->post('person'));

		$count = 0;

		$count += $this->person->update($person);

		if (isset($person->photo)) {
			$count += $this->photo->set_person($person->photo, $person->id);
		}

		if ($person->card > 0) {
			$count += $this->card->set_holder($person->card, $person->id);

			$card = $this->card->get($person->card);

			$ctrls = $this->ctrl->get_all($this->first_org->id);
			foreach ($ctrls as $ctrl) {
				$this->task->add_cards($ctrl->id, [$card->wiegand]);
			}
		}

		echo $count;
	}

	/**
	 * Удаляет человека
	 *
	 * @param int $person_id ID человека
	 */
	public function delete(int $person_id)
	{
		if (! $this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$cards = $this->card->get_by_person($person_id);
		if (count($cards) > 0) {
			$ctrls = $this->ctrl->get_all($this->first_org->id);

			foreach ($cards as $card) {
				$this->card->delete($card->id);

				foreach ($ctrls as $ctrl) {
					$this->task->delete_cards($ctrl->id, [$card->wiegand]);
				}
			}
		}

		$photo = $this->photo->get_by_person($person_id);
		if (isset($photo)) {
			$this->photo->delete($photo->id);
		}

		$this->div->delete_persons([$person_id]);

		echo $this->person->delete($person_id);
	}

	/**
	 * Получает человека
	 *
	 * @param int $person_id ID человека
	 */
	public function get(int $person_id)
	{
		header('Content-Type: application/json');

		echo json_encode(
			$this->person->get($person_id)
		);
	}

	/**
	 * Получает человека по карте
	 *
	 * @param int $card_id ID карты
	 */
	public function get_by_card(int $card_id)
	{
		$card = $this->card->get($card_id);

		header('Content-Type: application/json');

		$person = $this->person->get($card->person_id);

		$divs = $this->person->get_divs($person->id);

		foreach ($divs as &$div) {
			$div = $this->div->get($div->div_id);
		}
		unset($div);

		$person->divs = $divs;

		echo json_encode($person);
	}

	/**
	 * Получает людей по подразделению
	 *
	 * @param int $div_id ID подразделения
	 */
	public function get_all(int $div_id)
	{
		header('Content-Type: application/json');

		echo json_encode(
			$this->person->get_all($div_id)
		);
	}
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Cards
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Org_model $org
 * @property Task_model $org
 */
class Cards extends CI_Controller
{
	/**
	 * @var int $user_id
	 */
	private $user_id;

	/**
	 * @var mixed[] $orgs
	 */
	private $orgs;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (! $this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		if (! $this->ion_auth->in_group(2) && ! $this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->load->model('ac/card_model', 'card');
		$this->load->model('ac/ctrl_model', 'ctrl');
		$this->load->model('ac/org_model', 'org');
		$this->load->model('ac/task_model', 'task');

		$this->user_id = $this->ion_auth->user()->row()->id;
		$this->orgs = $this->org->get_list($this->user_id); //TODO
	}

	/**
	 * Закрепляет карту за человеком
	 *
	 * @param int $card_id   ID карты
	 * @param int $person_id ID человека
	 */
	public function holder(int $card_id, int $person_id = 0)
	{
		$card = $this->card->get($card_id);

		$card->person_id = $person_id;

		$ctrls = $this->ctrl->get_list(current($this->orgs)->id);

		if ($card->person_id === 0) {
			foreach ($ctrls as $ctrl) {
				$this->task->delete_cards($ctrl->id, [$card->wiegand]);
			}
		} else {
			foreach ($ctrls as $ctrl) {
				$this->task->add_cards($ctrl->id, [$card->wiegand]);
			}
		}

		echo $this->card->update($card);
	}

	/**
	 * Удаляет карту
	 *
	 * @param int $card_id ID карты
	 */
	public function delete($card_id)
	{
		echo $this->card->delete($card_id);
	}

	/**
	 * Удаляет все неизвестные карты
	 */
	public function delete_all_unknowns()
	{
		if (! $this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$cards = $this->card->get_list();

		$count = 0;

		foreach ($cards as $card) {
			if ($card->person_id == -1) {
				$count += $this->card->delete($card->id); //TODO события???
			}
		}

		echo $count;
	}

	/**
	 * Получает все неизвестные карты
	 */
	public function get_list()
	{
		header('Content-Type: application/json');

		echo json_encode(
			$this->card->get_by_person()
		);
	}

	/**
	 * Получает карты конкретного человека
	 *
	 * @param int $person_id ID человека
	 */
	public function get_by_person(int $person_id)
	{
		header('Content-Type: application/json');

		echo json_encode(
			$this->card->get_by_person($person_id)
		);
	}
}

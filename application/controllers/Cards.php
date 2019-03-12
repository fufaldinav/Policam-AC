<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Cards
 * @property Card_model $card
 */
class Cards extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->load->model('ac/card_model', 'card');
	}

	/**
	 * Добавление карты
	 *
	 * @param int $card_id   ID карты
	 * @param int $person_id ID человека
	 */
	public function holder(int $card_id, int $person_id = -1)
	{
		echo $this->card->set_holder($card_id, $person_id);
	}

	/**
	 * Удаление карты
	 *
	 * @param int $card_id ID карты
	 */
	public function delete($card_id)
	{
		echo $this->card->delete($card_id);
	}

	/**
	 * Получение информации о всех картах
	 */
	public function get_all()
	{
		header('Content-Type: application/json');

		echo json_encode(
			$this->card->get_by_person()
		);
	}

	/**
	 * Получение информации о картах конкретного человека
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

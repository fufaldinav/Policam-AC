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

		$this->load->model('ac/card_model', 'card');
	}

	/**
	 * Добавление карты
	 *
	 * @param int $card_id   ID карты
	 * @param int $person_id ID человека
	 */
	public function add($card_id, $person_id)
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			$this->output->set_header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->output->set_output($this->card->set_holder($card_id, $person_id));
	}

	/**
	 * Удаление карты
	 *
	 * @param int $card_id ID карты
	 */
	public function delete($card_id)
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			$this->output->set_header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->output->set_output($this->card->delete($card_id));
	}

	/**
	 * Получение информации о всех картах
	 */
	public function get_all()
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			$this->output->set_header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->card->get_by_holder()));
	}

	/**
	 * Получение информации о картах конкретного человека
	 *
	 * @param int $holder_id ID человека
	 */
	public function get_by_person($holder_id)
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			$this->output->set_header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->card->get_by_holder($holder_id)));
	}
}

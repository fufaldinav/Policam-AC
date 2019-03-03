<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Cards
 * @property  Card_model    $card
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
	 * @param  int  $card_id
	 * @param  int  $person_id
	 */
	public function add($card_id, $person_id)
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
	public function delete($card_id)
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
	 * Получение информации о всех картах
	 */
	public function get_all()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		echo json_encode($this->card->get_by_holder());
	}

	/**
	 * Получение информации о картах конкретного человека
	 *
	 * @param  int  $holder_id
	 */
	public function get_by_person($holder_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$cards = $this->card->get_by_holder($holder_id);

		if ($cards) {
			echo json_encode($cards);
		}
	}
}

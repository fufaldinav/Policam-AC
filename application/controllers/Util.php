<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Util
 * @property Ac_model $ac
 * @property Util_model $util
 */
class Util extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('language');

		$this->load->model('ac/other_model', 'other'); //TODO
		$this->load->model('ac/util_model', 'util');

		$this->lang->load('ac');
	}

	/**
	 * Получение времени сервера
	 */
	public function get_time()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		echo time();
	}

	/**
	 * Получение событий и начало long polling
	 */
	public function get_events()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$time = $this->input->post('time');
		$events = $this->input->post('events');

		echo json_encode([
			'msgs' => $this->util->start_polling($time, $events),
			'time' => now('Asia/Yekaterinburg')
		]);
	}

	/**
	 * Сохранение ошибок от клиентов
	 *
	 * @param string|null $err Текст ошибки, по-умолчанию получить POST-запрос
	 */
	public function save_js_errors($err = null)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		if ($err === null) {
			$err = $this->input->post('error');
		}

		$this->util->save_errors($err);
	}

	/**
	 * Обработка информации о проблеме с картой
	 */
	public function card_problem()
	{
		$this->load->model('ac/card_model', 'card');
		$this->load->model('ac/person_model', 'person');
		$this->load->model('ac/util_model', 'util');

		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$type = $this->input->post('type');
		$person_id = $this->input->post('person_id');

		if (!isset($type) || !isset($person_id)) {
			return null;
		}

		$response = lang('registred');

		if ($type >= 1 && $type <= 3) {
			$pers = $this->person->get($person_id);
		}

		if ($type == 1) {
			$desc = $pers->id . ' ' . $pers->f . ' ' . $pers->i . ' forgot card';

			if ($this->util->add_user_event($type, $desc)) {
				echo $response;
			}
		} elseif ($type == 2 || $type == 3) {
			$cards = $this->card->get_by_holder($person_id);

			if (!isset($cards)) {
				return null;
			}

			foreach ($cards as $card) {
				$this->other->delete_card($card->id);
			}

			$desc = $pers->id . ' ' . $pers->f . ' ' . $pers->i . ' lost/broke card';

			$response .= ' ' . lang('and') . ' ' . lang('card_deleted');

			if ($this->util->add_user_event($type, $desc)) {
				echo $response;
			}
		} else {
			return null;
		}
	}
}

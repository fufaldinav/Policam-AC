<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Util
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 * @property Util_model $util
 */
class Util extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$this->lang->load('ac');

		$this->load->model('ac/util_model', 'util');

		$this->load->helper('language');
	}

	/**
	 * Получение времени сервера
	 */
	public function get_time()
	{
		echo time();
	}

	/**
	 * Получение событий и начало long polling
	 */
	public function get_events()
	{
		$time = $this->input->post('time');
		$events = $this->input->post('events');

		header('Content-Type: application/json');

		echo json_encode([
				'msgs' => $this->util->start_polling($time, $events),
				'time' => now('Asia/Yekaterinburg')
		]);
	}

	/**
	 * Сохранение ошибок от клиентов
	 *
	 * @param string|null $err Текст ошибки или NULL - получить POST-запрос
	 */
	public function save_js_errors($err = null)
	{
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
		$this->load->model('ac/ctrl_model', 'ctrl');
		$this->load->model('ac/div_model', 'div');
		$this->load->model('ac/org_model', 'org');
		$this->load->model('ac/person_model', 'person');

		$type = $this->input->post('type');
		$person_id = $this->input->post('person_id');

		if ($type === null || $person_id === null) {
			return null;
		}

		$response = lang('registred');

		$person = $this->person->get($person_id);

		if ($type == 1) {
			$desc = $person->id . ' ' . $person->f . ' ' . $person->i . ' forgot card';

			if ($this->util->add_user_event($type, $desc)) {
				echo $response;
			}
		} elseif ($type == 2 || $type == 3) {
			$cards = $this->card->get_by_holder($person->id);

			if ($cards === null) {
				return null;
			}

			$div = $this->div->get($person->div);
			$org = $this->org->get($div->org_id);
			$ctrls = $this->ctrl->get_all($org->id);

			foreach ($cards as $card) {

				$this->card->set_holder($card->id, -1);

				foreach ($ctrls as $ctrl) {
					$this->ctrl->delete_cards($ctrl->id, $card->wiegand);
				}
			}

			$desc = $person->id . ' ' . $person->f . ' ' . $person->i . ' lost/broke card';

			$response .= ' ' . lang('and') . ' ' . lang('card_deleted');

			if ($this->util->add_user_event($type, $desc)) {
				echo $response;
			}
		}
	}
}

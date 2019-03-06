<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Controllers
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 */
class Controllers extends CI_Controller
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

		$this->load->model('ac/ctrl_model', 'ctrl');
		$this->load->model('ac/org_model', 'org');

		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$this->user_id = $this->ion_auth->user()->row()->id;
		$this->orgs = $this->org->get_all($this->user_id); //TODO
		$this->first_org = array_shift($this->orgs); //TODO
	}

	/**
	 * Установка времени открытия
	 *
	 * @param int|null $ctrl_id   ID контроллера
	 * @param int|null $open_time Время открытия в 0.1 сек
	 */
	public function set_door_params($ctrl_id = null, $open_time = null)
	{
		if (!$this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		if ($ctrl_id !== null && $open_time !== null) {
			if ($this->ctrl->set_door_params($ctrl_id, $open_time)) {
				echo 'Задания успешно отправлены'; //TODO перевод
			}
		} else {
			echo 'Не выбран контроллер или не задано время открытия'; //TODO перевод
		}
	}

	/**
	 * Удаление всех карт их контроллера
	 *
	 * @param int|null $ctrl_id ID контроллера
	 */
	public function clear($ctrl_id = null) {
		if (!$this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		if ($ctrl_id === null) {
			echo 'Не выбран контроллер'; //TODO перевод
		} else {
			if ($this->ctrl->clear_cards($ctrl_id)) {
				echo 'Задания успешно отправлены'; //TODO перевод
			}
		}
	}

	/**
	 * Выгрузка всех карт в контроллер
	 *
	 * @param int|null $ctrl_id ID контроллера
	 */
	public function reload_cards($ctrl_id = null)
	{
		if (!$this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		if ($ctrl_id === null) {
			echo 'Не выбран контроллер'; //TODO перевод
		} elseif ($this->first_org === null) {
			echo 'Нет организаций'; //TODO перевод
		} else {
			$this->load->model('ac/card_model', 'card');
			$this->load->model('ac/div_model', 'div');
			$this->load->model('ac/person_model', 'person');

			$cards = [];

			$divs = $this->div->get_all($this->first_org->id);

			foreach ($divs as &$div) {
				$div->persons = $this->person->get_all($div->id);

				foreach ($div->persons as &$person) {
					$person->cards = $this->card->get_by_holder($person->id);

					if ($person->cards !== null) {
						$cards = array_merge($cards, $person->cards);
					}
				}
			}

			$card_count = count($cards);
			$counter = 0;
			$codes = [];
			for ($i = 0; $i < $card_count; $i++) {
				$codes[] = $cards[$i]->wiegand;

				if (($i > 0 && ($i % 10 == 0)) || $i == ($card_count - 1)) {
					if ($this->ctrl->add_cards($ctrl_id, $codes)) {
						$counter++;
					}
					$codes = [];
				}
			}

			echo 'Отправлено заданий: ' . $counter; //TODO перевод
		}
	}
}

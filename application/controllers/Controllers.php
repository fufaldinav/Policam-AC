<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Controllers
 * @property Controller_model $card
 */
class Controllers extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ac/controller_model', 'controller');
	}

	/**
	 * Установка времени открытия
	 *
	 * @param int|null $controller_id ID контроллера
	 * @param int|null $open_time     Время открытия
	 */
	public function set_door_params($controller_id = null, $open_time = null)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}
		if (isset($controller_id) && isset($open_time)) {
			echo $this->controller->set_door_params($controller_id, $open_time);
			echo ' заданий записано'; //TODO перевод
		} else {
			echo 'Не выбран контроллер или не задано время открытия'; //TODO перевод
		}
	}

	/**
	 * Выгрузка всех карт в контроллер
	 *
	 * @param int|null $controller_id ID контроллера
	 */
	public function reload_cards($controller_id = null)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->is_admin()) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}
		if (isset($controller_id)) {
			echo $this->ac->add_all_cards_to_controller($controller_id);
			echo ' заданий записано'; //TODO
		} else {
			echo 'Не выбран контроллер'; //TODO
		}
	}
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Server
 * @property Server_model $server
 */
class Server extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ac/server_model', 'server');
	}

	/**
	 * Обрабатывает сообщения от контроллера
	 */
	public function index()
	{
		$inc_json_msg = file_get_contents('php://input');

		$out_json_msg = $this->server->handle_msg($inc_json_msg);

		header('Content-Type: application/json');

		echo $out_json_msg ?? '';
	}
}

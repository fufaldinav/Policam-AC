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
	}

	/**
	 * Обработчик сообщений от контроллеров
	 */
	public function index()
	{
		$inc_msg = file_get_contents('php://input');

		$out_msg = $this->server->handle_msg($inc_msg);

		if ($out_msg !== null) {
			header('Content-Type: application/json');
			echo json_encode($out_msg);
		}
	}
}

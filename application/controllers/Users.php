<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Notification
 * @property Notification_model $notification
 */
class Users extends CI_Controller
{
	/**
	 * @var int $user_id
	 */
	private $user_id;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		$this->load->model('ac/notification_model', 'notification');

		$this->user_id = $this->ion_auth->user()->row()->id;
	}

	/**
	 * Получение токенов от пользователя
	 */
	public function token()
	{
		$token_str = $this->input->post('token');

		if ($token_str === 'false') {
			$this->notification->delete_token($token_str);
		} elseif (!isset($this->notification->get_token($token_str))) {
			$this->notification->add_token($this->user_id, $token_str);
		}
	}
}

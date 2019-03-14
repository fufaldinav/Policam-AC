<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Users
 * @property Token_model $token
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

		if (! $this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$this->load->model('ac/token_model', 'token');

		$this->user_id = $this->ion_auth->user()->row()->id;
	}

	/**
	 * Получает токен от пользователя
	 */
	public function token()
	{
		$token = $this->input->post('token');

		if ($token_key === 'false') {
			$this->token->delete($token_key);
		} elseif (! isset($this->token->get($token_key))) {
			$this->token->add($this->user_id, $token_key);
		}
	}
}

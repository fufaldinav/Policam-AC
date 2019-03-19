<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Users
 *
 * @property Token_model $token
 */
class Users extends CI_Controller
{
    /**
     * @var int
     */
    private $_user_id;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $this->_user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * Получает токен от пользователя
     */
    public function token()
    {
        $this->ac->model('token');

        $token = $this->input->post('token');

        if ($token_key === 'false') {
            // $this->token->get_by('token', $token_key);
            // $this->token->delete(); //TODO удалять просроченный ключ
        } elseif (! ($this->token->get_by('token', $token_key))) {
            $this->token->user_id = $this->_user_id;
            $this->token->token = $token_key;
            $this->token->save();
        }
    }
}

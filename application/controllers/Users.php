<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Users
 */
class Users extends CI_Controller
{
    /**
     * Текущий пользователь
     *
     * @var int
     */
    private $_user;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->_user = new \Orm\Users($user_id);
    }

    /**
     * Получает токен от пользователя
     */
    public function token()
    {
        $this->ac->load('Tokens');

        $token_key = $this->input->post('token');



        if ($token_key === 'false') {
            // $this->token->get_by('token', $token_key);
            // $this->token->delete(); //TODO удалять просроченный ключ
        } else {
            $token = new \Orm\Tokens(['token' => $token_key]);

            $token->user_id = $this->_user_id;
            $token->token = $token_key;

            $token->save();
        }
    }
}

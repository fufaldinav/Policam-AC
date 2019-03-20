<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Server
 *
 * @property Messenger $messenger
 */
class Server extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Обрабатывает сообщения от контроллера
     */
    public function index()
    {
        $this->load->library('messenger');
        $inc_json_msg = file_get_contents('php://input');

        $out_json_msg = $this->messenger->handle($inc_json_msg);

        header('Content-Type: application/json');

        echo $out_json_msg ?? '';
    }
}

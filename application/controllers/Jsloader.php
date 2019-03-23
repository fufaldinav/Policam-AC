<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Jsloader
 *
 * @property Jsparser $jsparser
 */
class Jsloader extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Принимает файл и отправляет на поиск переменных
     *
     * @param mixed $file
     *
     * @return void
     */
    public function file($file = null): void
    {
        $this->load->library('jsparser');

        if (is_null($file)) {
            header('HTTP/1.1 404 Not Found');
            exit;
        }

        $contents = $this->jsparser->parse($file);

        if (is_null($contents)) {
            header('HTTP/1.1 404 Not Found');
            exit;
        }

        header('Content-Type: text/javascript');
        echo $contents;
    }
}

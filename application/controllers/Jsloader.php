<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Jsloader
 *
 * @property Ac $ac
 * @property Jsparser $jsparser
 */
class Jsloader extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('ac');
        $this->load->library('jsparser');
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
        if (! isset($file)) {
            header('HTTP/1.1 404 Not Found');
            exit;
        }

        $contents = $this->jsparser->parse($file);

        if (! $contents) {
            header('HTTP/1.1 404 Not Found');
            exit;
        } else {
            header('Content-Type: text/javascript');
            echo $contents;
        }
    }
}

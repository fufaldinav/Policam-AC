<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 17.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.2 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Logger
 */
class Logger extends Ac
{
    /**
     * Каталог с логами
     *
     * @var string
     */
    private $_log_path;

    /**
     * Таймаут одного long poll
     *
     * @var int
     */
    private $_timeout;

    public function __construct()
    {
        parent::__construct();

        $this->_log_path = $this->CI->config->item('log_path', 'ac');

        if (! is_dir($this->_log_path)) {
            mkdir($this->_log_path, 0755, true);
        }

        $this->_timeout = $this->CI->config->item('long_poll_timeout', 'ac');
    }

    /**
     * Сохраняет ошибки
     *
     * @param string $err Текст ошибки
     */
    public function save_errors(string $err): void
    {
        $this->load->helper('file');

        $time = now('Asia/Yekaterinburg');
        $datestring = '%Y-%m-%d';
        $date = mdate($datestring, $time);
        $timestring = '%H:%i:%s';
        $time = mdate($timestring, $time);

        write_file("$this->_log_path/err-$date.txt", "$time $err\n", 'a');
    }
}

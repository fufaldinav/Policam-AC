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
     * Записи
     *
     * @var array
     */
    private $_to_write = [];

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->_log_path = $this->_CI->config->item('log_path', 'ac');

        if (! is_dir($this->_log_path)) {
            mkdir($this->_log_path, 0755, true);
        }

        $this->_timeout = $this->_CI->config->item('long_poll_timeout', 'ac');
    }

    /**
     * Записывает в лог
     *
     * @return bool TRUE - успешно, FALSE - ошибка или нет данных для записи
     */
    public function write(): bool
    {
        if (! $this->_to_write) {
            return false;
        }

        foreach ($this->_to_write as $entry) {
            write_file("$this->_log_path/{$entry['category']}-{$entry['date']}.txt", "[{$entry['time']}] {$entry['message']}\n", 'a');
        }

        $this->_to_write = [];

        return true;
    }

    /**
     * Добавляет сообщение на запись
     *
     * @param string $category Категория ошибки
     * @param string $message Текст ошибки
     *
     * @return void
     */
    public function add(string $category, string $message): void
    {
        $this->_CI->load->helper(['date', 'file']);

        $time = now('Asia/Yekaterinburg');
        $date = mdate('%Y-%m-%d', $time);
        $time = mdate('%H:%i:%s', $time);

        $this->_to_write[] = [
            'category' => $category,
            'date' => $date,
            'time' => $time,
            'message' => $message
        ];
    }
}

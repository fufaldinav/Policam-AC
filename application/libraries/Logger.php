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
    private $log_path;

    /**
     * Записи
     *
     * @var array
     */
    private $to_write = [];

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->log_path = $this->CI->config->item('log_path', 'ac');

        if (! is_dir($this->log_path)) {
            mkdir($this->log_path, 0755, true);
        }
    }

    /**
     * Записывает в лог
     *
     * @return bool TRUE - успешно, FALSE - ошибка или нет данных для записи
     */
    public function write(): bool
    {
        if (! $this->to_write) {
            return false;
        }

        foreach ($this->to_write as $entry) {
            write_file("$this->log_path/{$entry['category']}-{$entry['date']}.txt", "[{$entry['time']}] {$entry['message']}\n", 'a');
        }

        $this->to_write = [];

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
        $this->CI->load->helper(['date', 'file']);

        $time = now();
        $date = mdate('%Y-%m-%d', $time);
        $time = mdate('%H:%i:%s', $time);

        $this->to_write[] = [
            'category' => $category,
            'date' => $date,
            'time' => $time,
            'message' => $message
        ];
    }
}

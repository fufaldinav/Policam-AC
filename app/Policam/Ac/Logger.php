<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 28.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */
namespace App\Policam\Ac;


class Logger
{
    /** @var string Каталог с логами */
    private $log_path;

    /** @var array Записи */
    private $to_write = [];

    public function __construct()
    {
        $this->log_path = config('ac.log_path');

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
            $fp = fopen("$this->log_path/{$entry['category']}-{$entry['date']}.txt", 'a');
            fwrite($fp, "[{$entry['time']}] {$entry['message']}\n");
            fclose($fp);
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
        $date = date('Y-m-d');
        $time = date('H:i:s');

        $this->to_write[] = [
            'category' => $category,
            'date' => $date,
            'time' => $time,
            'message' => $message
        ];
    }
}

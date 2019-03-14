<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 02.03.2019
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
 * Class Task Model
 */
class Task_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    /**
     * Добавляет задания для отправки на контроллер
     *
     * @param string      $operation Операция, отправляемая на контроллер
     * @param int         $ctrl_id   ID контроллера
     * @param string|null $data      Дополнительные данные
     *
     * @return int Количество успешных записей
     */
    public function add(string $operation, int $ctrl_id, string $data = null): int
    {
        $id = mt_rand(500000, 999999999);

        $json = '{"id":' . $id . ',';
        $json .= '"operation":"' . $operation . '"';
        $json .= isset($data) ? ',' : '';
        $json .= isset($data) ? $data : '';
        $json .= '}';

        $data =	[
            'id' => $id,
            'controller_id' => $ctrl_id,
            'json' => $json,
            'time' => now('Asia/Yekaterinburg')
        ];

        $this->db->insert('tasks', $data);

        return $this->db->affected_rows();
    }

    /**
     * Удаляет задания, отправленные на контроллер
     *
     * @param int $task_id ID задания
     *
     * @return int Количество успешных удалений
     */
    public function delete(int $task_id): int
    {
        $this->db
            ->where('id', $task_id)
            ->delete('tasks');

        return $this->db->affected_rows();
    }

    /**
     * Получает последнее задание для отправки на контроллер
     *
     * @param int $ctrl_id ID контроллера
     *
     * @return object|null Задание или NULL, если не найден
     */
    public function get_last(int $ctrl_id): ?object
    {
        $query = $this->db
            ->where('controller_id', $ctrl_id)
            ->order_by('time', 'ASC')
            ->get('tasks');

        return $query->row();
    }

    /**
     * Установливает параметры открытия
     *
     * @param int $ctrl_id       ID контроллера
     * @param int $open_time     Время открытия в 0.1 сек
     * @param int $open_control  Контроль открытия в 0.1 сек, по-умолчанию 0 - без контроля
     * @param int $close_control Контроль закрытия в 0.1 сек, по-умолчанию 0 - без контроля
     *
     * @return int Количество успешных записей
     */
    public function set_door_params(int $ctrl_id, int $open_time, int $open_control = 0, int $close_control = 0): int
    {
        $data = sprintf('"open": %d, "open_control": %d, "close_control": %d', $open_time, $open_control, $close_control);

        return $this->add('set_door_params', $ctrl_id, $data);
    }

    /**
     * Добавляет карты в контроллер
     *
     * @param int      $ctrl_id ID контроллера
     * @param string[] $codes   Коды карт
     *
     * @return int Количество успешных записей
     */
    public function add_cards(int $ctrl_id, array $codes): int
    {
        $data = '"cards": [';

        foreach ($codes as $code) {
            $data .= '{"card":"' . $code . '","flags":32,"tz":255},';
        }
        $data = substr($data, 0, -1);

        $data .= ']';

        return $this->add('add_cards', $ctrl_id, $data);
    }

    /**
     * Удаляет карты из контроллера
     *
     * @param int      $ctrl_id ID контроллера
     * @param string[] $codes   Коды карт
     *
     * @return int Количество успешных удалений
     */
    public function delete_cards(int $ctrl_id, array $codes): int
    {
        $data = '"cards": [';

        foreach ($codes as $code) {
            $data .= '{"card":"' . $code . '"},';
        }
        $data = substr($data, 0, -1);

        $data .= ']';

        return $this->add('del_cards', $ctrl_id, $data);
    }


    /**
     * Удаляет все карты из контроллера
     *
     * @param int $ctrl_id ID контроллера
     *
     * @return int Количество успешных удалений
     */
    public function clear_cards(int $ctrl_id): int
    {
        return $this->add('clear_cards', $ctrl_id);
    }
}

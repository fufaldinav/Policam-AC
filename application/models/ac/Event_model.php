<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 15.03.2019
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
 * Class Event Model
 */
class Event_model extends MY_Model
{
    /**
     * Контроллер, от которого пришло событие
     *
     * @var int
     */
    public $controller_id;

    /**
     * Тип события
     *
     * @var int
     */
    public $event;

    /**
     * Флаг события
     *
     * @var int
     */
    public $flag;

    /**
     * Время события на контроллере
     *
     * @var int
     */
    public $time;

    /**
     * Время получения события сервером
     *
     * @var int
     */
    public $server_time;

    /**
     * Карта, вызвавшая событие
     *
     * @var int
     */
    public $card_id;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'test';
        $this->_foreing_key = 'controller_id';
    }

    /**
     * Получает список последних событий из БД, пришедших после установленного
     * времени, фильтруя список по типу событий и контроллерам
     *
     * @param int        $time        Время в секундах
     * @param array|null $event_types Типы событий
     * @param array|null $controllers ID контроллеров
     *
     * @return object[] Ноый список объектов
     */
    public function list_get_last(
        int $time,
        array $event_types = null,
        array $controllers = null
    ): array {
        if (isset($event_types)) {
            $this->CI->db->where_in('event', $event_types);
        }
        if (isset($controllers)) {
            $this->CI->db->where_in($this->_foreing_key, $controllers);
        }

        return $this->list = $this->CI->db->where('server_time >', $time)
                                          ->order_by('time', 'DESC')
                                          ->get('events')
                                          ->result();
    }

    /**
     * Выделяет нужные для записи в БД свойства
     *
     * @return mixed[] Массив с параметрами карты
     */
    protected function _get_array(): array
    {
        $data = [
            'controller_id' => $this->controller_id,
            'event' => $this->event,
            'flag' => $this->flag,
            'time' => $this->time,
            'server_time' => $this->server_time,
            'card_id' => $this->card_id
        ];

        return $data;
    }
}

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
 * Class Ctrl Model
 */
class Ctrl_model extends MY_Model
{
    /**
     * @var string
     */
    public $type = 'Z5RWEB';

    /**
     * @var int
     */
    public $mode = 0;

    /**
     * @var int
     */
    public $protocol = 1;

    /**
     * @var int
     */
    public $active = 1;

    /**
     * @var int
     */
    public $online = 0;

    /**
     * @var int
     */
    public $last_conn = 0;

    /**
     * @var int
     */
    public $org_id = 0;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'controllers';
        $this->_foreing_key = 'org_id';
    }

    /**
     * Выделяет нужные для записи в БД свойства
     *
     * @return mixed[] Массив с параметрами контроллера
     */
    protected function _get_array(): array
    {
        $data = [
            'name' => $this->name,
            'sn' => $this->sn,
            'type' => $this->type,
            'fw' => $this->fw,
            'conn_fw' => $this->conn_fw,
            'mode' => $this->mode,
            'ip' => $this->ip,
            'protocol' => $this->protocol,
            'active' => $this->active,
            'online' => $this->online,
            'last_conn' => $this->last_conn,
            'org_id' => $this->org_id
        ];

        return $data;
    }
}

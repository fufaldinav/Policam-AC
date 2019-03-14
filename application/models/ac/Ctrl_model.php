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
    public function __construct()
    {
        parent::__construct();

        $this->_table = 'controllers';
        $this->_foreing_key = 'org_id';
    }

    /**
     * Выделяет нужные свойства для записи в БД
     *
     * @return mixed[] Массив с параметрами контроллера
     */
    protected function _set(): array
    {
        $data = [
            'name' => $this->name,
            'sn' => $this->sn,
            'type' => $this->type ?? 'Z5RWEB',
            'fw' => $this->fw,
            'conn_fw' => $this->conn_fw,
            'mode' => $this->mode ?? 0,
            'ip' => $this->ip,
            'protocol' => $this->protocol ?? 1,
            'active' => $this->active ?? 1,
            'online' => $this->online ?? 0,
            'last_conn' => $this->last_conn ?? 0,
            'org_id' => $this->org_id ?? 0
        ];

        return $data;
    }
}

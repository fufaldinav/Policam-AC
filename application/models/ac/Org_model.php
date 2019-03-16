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
 * Class Org Model
 */
class Org_model extends MY_Model
{
    /**
     * Название организации
     *
     * @var string
     */
    public $name;

    /**
     * Адрес организации
     *
     * @var string
     */
    public $address;

    /**
     * Тип организации
     *
     * @var int
     */
    public $type = 1;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'organizations';
        $this->_foreing_key = 'user_id';
    }

    /**
     * Получает список всех организаций по пользователю
     *
     * @param int|null $user_id ID пользователя
     *
     * @return object[] Список организаций или текущий список, если $user_id не указан
     */
    public function get_list(int $user_id = null): array
    {
        if (! isset($user_id)) {
            return $this->_list;
        }
        return $this->_list = $this->CI->db->where($this->_foreing_key, $user_id)
                                          ->join($this->_table, "$this->_table.$this->_primary_key = organizations_users.org_id", 'left')
                                          ->order_by('name', 'ASC')
                                          ->get('organizations_users')
                                          ->result();
    }

    /**
     * Получает пользователей организации
     *
     * @param int $org_id ID орагнизации
     *
     * @return int[] Список ID пользователей
     */
    public function get_users(int $org_id): array
    {
        return $this->users = $this->CI->db->select($this->_foreing_key)
                                           ->where('org_id', $org_id)
                                           ->get('organizations_users')
                                           ->result();
    }

    /**
     * Возвращает первую организацию из списка или свойство первой организации
     *
     * @param string|null $property Свойство
     *
     * @return mixed|null Организация
     */
    public function first($property = null)
    {
        if (count($this->_list) === 0) {
            return null;
        }

        if (isset($property)) {
            return $this->_list[0]->$property;
        }

        return $this->_list[0];
    }
}

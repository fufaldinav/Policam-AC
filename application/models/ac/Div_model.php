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
 * Class Div Model
 */
class Div_model extends MY_Model
{
    /**
     * Название подразделения
     *
     * @var string
     */
    public $name;

    /**
     * Организация, которой принадлежит подразделение
     *
     * @var int
     */
    public $org_id;

    /**
     * Тип подразделения
     *
     * @var int
     */
    public $type = 1;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'divisions';
        $this->_foreing_key = 'org_id';
    }

    /**
     * Получает список подразделений по ID организации из БД
     *
     * @param int|null $org_id ID организации
     *
     * @return object[] Список подразделений
     */
    public function get_list(int $org_id = null): array
    {
        $this->CI->db->order_by('type ASC, CAST(name AS UNSIGNED) ASC, name ASC');

        return parent::get_list($org_id);
    }

    /**
     * Получает список подразделений по типу и ID организации из БД
     *
     * @param int|null $org_id ID организации
     * @param int      $type   Тип подразделения, по-умолчанию 0 - подразделения-пустышки
     *
     * @return object[] Список подразделений
     */
    public function get_list_by_type(int $org_id = null, int $type = 0): array
    {
        $this->CI->db->where('type', $type)
                     ->order_by('type ASC, CAST(name AS UNSIGNED) ASC, name ASC');

        return parent::get_list($org_id);
    }

    /**
     * Удаляет подразделение и все связи с людьми
     *
     * @param int|null $div_id ID подразделения
     *
     * @return int Количество успешных удалений
     */
    public function delete(int $div_id = null): int
    {
        $this->CI->db->delete('persons_divisions', ['div_id' => $div_id ?? $this->id]);

        return parent::delete($div_id);
    }
}

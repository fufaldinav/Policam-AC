<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
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
 * Class Person Model
 */
class Person_model extends MY_Model
{
    /**
     * Фамилия
     *
     * @var string
     */
    public $f;

    /**
     * Имя
     *
     * @var string
     */
    public $i;

    /**
     * Отвество
     *
     * @var string
     */
    public $o;

    /**
     * Тип/должность человека
     *
     * @var int
     */
    public $type = 1;

    /**
     * Дата рождения
     *
     * @var string
     */
    public $birthday;

    /**
     * Адрес
     *
     * @var string
     */
    public $address;

    /**
     * Номер телефона
     *
     * @var string
     */
    public $phone;

    /**
     * Фотография
     *
     * @var string
     */
    public $photo;

    /**
     * Подразделения
     *
     * @var array
     */
    private $divs = [];

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'persons';
        $this->_foreing_key = 'div_id';
    }

    /**
     * Получает человека по ID из БД
     *
     * @param int $person_id ID человека
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function get(int $person_id = 0): bool
    {
        $this->CI->db->select("$this->_primary_key, address, birthday, f, i, o, phone, type")
                     ->select("photo_id AS 'photo'");

        return parent::get($person_id);
    }

    /**
     * Получает список всех людей по подразделению из БД
     *
     * @param int|null $div_id ID подразделения
     *
     * @return object[] Список людей или текущий список, если $div_id не указан
     */
    public function get_list(int $div_id = null): array
    {
        if (! isset($div_id)) {
            return $this->_list;
        }

        return $this->_list = $this->CI->db->where($this->_foreing_key, $div_id)
                                          ->join($this->_table, "$this->_table.$this->_primary_key = persons_divisions.person_id", 'left')
                                          ->order_by('f ASC, i ASC, o ASC')
                                          ->get('persons_divisions')
                                          ->result();
    }

    /**
     * Получает список ID подразделений человека из БД
     *
     * @param int|null $person_id ID человека
     *
     * @return int[] Список ID подразделений или текущий список, если $person_id не указан
     */
    public function get_divs(int $person_id = null): array
    {
        if (! isset($person_id)) {
            return $this->divs;
        }

        return $this->divs = $this->CI->db->select($this->_foreing_key)
                                          ->where('person_id', $person_id)
                                          ->get('persons_divisions')
                                          ->result();
    }

    /**
     * Добавляет человека в подразделение
     *
     * @param int $person_id ID человека
     * @param int $div_id    ID подразделения
     *
     * @return int Количество успешных записей
     */
    public function add_to_div(int $person_id, int $div_id): int
    {
        $this->CI->db->insert('persons_divisions', [
            'person_id' => $person_id,
            $this->_foreing_key => $div_id
        ]);

        return $this->CI->db->affected_rows();
    }

    /**
     * Удаляет человека из подразделения
     *
     * @param int      $person_id ID человека
     * @param int|null $div_id    ID подразделения
     *
     * @return int Количество успешных удалений
     */
    public function del_from_div(int $person_id, int $div_id = null): int
    {
        if (isset($div_id)) {
            $this->CI->db->where($this->_foreing_key, $div_id);
        }
        $this->CI->db->where('person_id', $person_id)
                     ->delete('persons_divisions');

        return $this->CI->db->affected_rows();
    }

    /**
     * Удаляет информацию о фотографии по ID человека, если ID не установлено, то удаляет по текущему человеку
     *
     * @param int|null $person_id ID человека
     *
     * @return int Количество успешных удалений
     */
    public function unset_photo(int $person_id = null): int
    {
        if (! isset($person_id)) {
            unset($this->photo);
        }

        $this->CI->db->where($this->_primary_key, $person_id ?? $this->id)
                     ->update($this->_table, ['photo_id' => null]);

        return $this->CI->db->affected_rows();
    }
}

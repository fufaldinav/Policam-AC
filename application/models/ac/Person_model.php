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
     * @var int
     */
    public $type = 1;

    /**
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
        $this->db->select("$this->_primary_key, address, birthday, f, i, o, phone, type")
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
            return $this->list;
        }

        return $this->list = $this->db->where($this->_foreing_key, $div_id)
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

        return $this->divs = $this->db->select($this->_foreing_key)
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
        $this->db->insert('persons_divisions', [
            'person_id' => $person_id,
            $this->_foreing_key => $div_id
        ]);

        return $this->db->affected_rows();
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
            $this->db->where($this->_foreing_key, $div_id);
        }
        $this->db
            ->where('person_id', $person_id)
            ->delete('persons_divisions');

        return $this->db->affected_rows();
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

        $this->db
            ->where($this->_primary_key, $person_id ?? $this->id)
            ->update($this->_table, ['photo_id' => null]);

        return $this->db->affected_rows();
    }

    /**
     * Выделяет нужные для записи в БД свойства
     *
     * @return mixed[] Массив с параметрами человека
     */
    protected function _get_array(): array
    {
        $data = [
            'f' => $this->f,
            'i' => $this->i,
            'o' => $this->o,
            'type' => $this->type,
            'birthday' => $this->birthday,
            'address' => $this->address,
            'phone' => $this->phone,
            'photo_id' => $this->photo
        ];

        return $data;
    }
}

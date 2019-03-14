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
class Person_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    /**
     * Получает человека по ID
     *
     * @param int $person_id ID человека
     *
     * @return object|null Человек или NULL, если не найдена
     */
    public function get(int $person_id): ?object
    {
        $query = $this->db->select('id, address, birthday, f, i, o, phone, type')
            ->select("photo_id AS 'photo'")
            ->where('id', $person_id)
            ->get('persons');

        return $query->row();
    }

    /**
     * Получает список всех людей по подразделению
     *
     * @param int|null $div_id ID подразделения
     *
     * @return object[] Массив с людьми или пустой массив
     */
    public function get_list(int $div_id = null): array
    {
        if (isset($div_id)) {
            $this->db->where('div_id', $div_id);
        }
        $query = $this->db
            ->join('persons', 'persons.id = persons_divisions.person_id', 'left')
            ->order_by('f ASC, i ASC, o ASC')
            ->get('persons_divisions');

        return $query->result();
    }

    /**
     * Получает подразделения человека
     *
     * @param int $person_id ID человека
     *
     * @return int[] Список ID подразделений
     */
    public function get_divs(int $person_id): array
    {
        $query = $this->db
            ->select('div_id')
            ->where('person_id', $person_id)
            ->get('persons_divisions');

        return $query->result();
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
            'div_id' => $div_id
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
            $this->db->where('div_id', $div_id);
        }
        $this->db
            ->where('person_id', $person_id)
            ->delete('persons_divisions');

        return $this->db->affected_rows();
    }

    /**
     * Добавляет нового человека
     *
     * @param object $person Человек
     *
     * @return int ID нового человека
     */
    public function add(object $person): int
    {
        $this->db->insert('persons', $this->_set($person));

        return $this->db->insert_id();
    }

    /**
     * Обновляет информацию о человеке
     *
     * @param object $person Человек
     *
     * @return int Количество успешных записей
     */
    public function update(object $person): int
    {
        $this->db
            ->where('id', $person->id)
            ->update('persons', $this->_set($person));

        return $this->db->affected_rows();
    }

    /**
     * Удаляет человека
     *
     * @param int $person_id ID человека
     *
     * @return int Количество успешных удалений
     */
    public function delete(int $person_id): int
    {
        $this->db->delete('persons', ['id' => $person_id]);

        return $this->db->affected_rows();
    }

    /**
     * Получает объект и возвращает массив для записи
     *
     * @param object $person Человек
     *
     * @return mixed[] Массив с параметрами человека
     */
    private function _set(object $person): array
    {
        $data = [
            'f' => $person->f,
            'i' => $person->i,
            'o' => $person->o,
            'type' => $person->type ?? 1,
            'birthday' => $person->birthday,
            'address' => $person->address,
            'phone' => $person->phone,
            'photo_id' => $person->photo
        ];

        return $data;
    }

    /**
     * Удаляет информацию о фотографии
     *
     * @param int $person_id ID человека
     *
     * @return int Количество успешных удалений
     */
    public function unset_photo(int $person_id): int
    {
        $this->db
            ->where('id', $person_id)
            ->update('persons', ['photo_id' => null]);

        return $this->db->affected_rows();
    }
}

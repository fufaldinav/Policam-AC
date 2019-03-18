<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 18.03.2019
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
 * Class MicroORM
 */
abstract class MicroORM
{
    /**
     * ID объекта
     *
     * @var int
     */
    public $id;

    /**
     * Суперобъект Codeigniter
     *
     * @var object
     */
    protected $CI;

    /**
     * Объект БД
     *
     * @var object
     */
    protected $db;

    /**
     * Таблица в БД
     *
     * @var string
     */
    protected $table;


    /**
     * Список дочерних объектов
     *
     * @var array
     */
    protected $list = [];

    /**
     * Хранилище неизвестных свойств
     *
     * @var array
     */
    protected $data = [];

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->db = $this->CI->db;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    /**
     * Получает объект по ID из БД
     *
     * @param int $id ID объекта
     *
     * @return object|false Объект класса или FALSE, если объект отсутвствует
     */
    public function get(int $id)
    {
        $query = $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();

        if (! isset($query)) {
            return false;
        }

        $this->_set($query);

        return true;
    }

    /**
     * Получает объект по параметрам из БД
     *
     * @param array $attr Набор параметров
     *
     * @return object|false Объект класса или FALSE, если объект отсутвствует
     */
    public function get_by(array $attr)
    {
        $query = $this->db
            ->where($attr)
            ->get($this->table)
            ->row();

        if (! isset($query)) {
            return false;
        }

        $this->_set($query);

        return true;
    }

    /**
     * Получает список объектов по ID элемента из БД
     *
     * @param int|null $item_id ID элемента
     *
     * @return object[] Новый список объектов или текущий список,
     *                  если $item_id не указан
     */
    public function get_list(int $item_id = null): array
    {
        if (! isset($this->_foreing_key) || ! isset($item_id)) {
            return $this->_list;
        }

        return $this->_list = $this->db
            ->where($this->_foreing_key, $item_id)
            ->get($this->table)
            ->result();
    }

    /**
     * Устанавливает свойства текущему объекту
     *
     * @param object $object Объект с набором свойств
     *
     * @return void
     */
    private function _set(object $object): void
    {
        foreach ($object as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Устанавливает свойства текущему объекту
     *
     * @param array|object $object Объект или массив с набором свойств
     *
     * @return void
     */
    public function set($object): void
    {
        if (! is_array($object) && ! is_object($object)) {
            throw new Exception('Argument 1 passed to ' . __METHOD__ . '() must be array or object, ' . gettype($object) . ' given, called in ' . __FILE__ . ' on line ' . __LINE__);
        }

        foreach ($object as $key => $value) {
            if ($key === 'id') {
                continue;
            }
            $this->$key = $value;
        }
    }

    /**
     * Сохраняет объект в БД
     *
     * @return int Количество успешных записей
     */
    public function save(): int
    {
        if (isset($this->id)) {
            $this->db->where('id', $this->id)
                     ->update($this->table, $this);
        } else {
            $this->db->insert($this->table, $this);

            $this->id = $this->db->insert_id();
        }

        return $this->db->affected_rows();
    }

    /**
     * Сохраняет список объектов в БД
     *
     * @return int Количество успешных записей
     */
    public function save_list(): int
    {
        $count = 0;

        $updatedata = [];

        foreach ($this->_list as $object) {
            if (isset($object->id)) {
                $updatedata[] = $object;
            } else {
                $count += $this->db->insert($this->table, $object);

                $object->id = $this->db->insert_id();
            }
        }

        if ($updatedata) {
            $count += $this->db->update_batch(
                $this->table,
                $updatedata,
                'id'
            );
        }

        return $count;
    }

    /**
     * Вносит новый объект в список, копируя свойства текущего
     *
     * @return void
     */
    public function add_to_list(): void
    {
        $object = new stdClass;

        foreach ($this as $key => $value) {
            $rp = new ReflectionProperty($this, $key);

            if ($rp->isPublic()) {
                $object->$key = $value;
            }
        }

        $this->_list[] = $object;
    }

    /**
     * Удаляет объект из БД
     *
     * @return int Количество успешных удалений
     */
    public function remove(): int
    {
        $this->db->delete($this->table, ['id' => $this->id]);

        return $this->db->affected_rows();
    }
}

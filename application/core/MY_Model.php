<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 14.03.2019
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
 * Class My Model
 */
class MY_Model extends CI_Model
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
     * Таблица в БД
     *
     * @var string
     */
    protected $_table;

    /**
     * Ключ в БД
     *
     * @var string
     */
    protected $_primary_key = 'id';

    /**
     * Родитель в БД
     *
     * @var string
     */
    protected $_foreing_key;

    /**
     * Список объектов
     *
     * @var array
     */
    protected $_list = [];

    /**
     * Хранилище неизвестных свойств
     *
     * @var array
     */
    protected $_data = [];

    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    public function __unset($name)
    {
        unset($this->_data[$name]);
    }

    /**
     * Получает объект по ID из БД
     *
     * @param int $id ID объекта
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function get(int $id = 0): bool
    {
        $query = $this->CI->db->where($this->_primary_key, $id)
                              ->get($this->_table)
                              ->row();

        if (! isset($query)) {
            return false;
        }

        $this->set($query);

        return true;
    }

    /**
     * Получает объект по признаку из БД
     *
     * @param string $attr  Признак
     * @param mixed  $value Значение признака
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function get_by(string $attr, $value): bool
    {
        $query = $this->CI->db->where($attr, $value)
                              ->get($this->_table)
                              ->row();

        if (! isset($query)) {
            return false;
        }

        $this->set($query);

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

        return $this->_list = $this->CI->db->where($this->_foreing_key, $item_id)
                                          ->get($this->_table)
                                          ->result();
    }

    /**
     * Устанавливает свойства текущему объекту
     *
     * @param array|object $object Объект или массив с набором свойств
     */
    public function set($object): void
    {
        if (! is_array($object) && ! is_object($object)) {
            throw new Exception('Argument 1 passed to ' . __METHOD__ . '() must be array or object, ' . gettype($object) . ' given, called in ' . __FILE__ . ' on line ' . __LINE__);
        }

        foreach ($object as $key => $value) {
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
            $this->CI->db->where($this->_primary_key, $this->id)
                         ->update($this->_table, $this);
        } else {
            $this->CI->db->insert($this->_table, $this);

            $this->id = $this->CI->db->insert_id();
        }

        return $this->CI->db->affected_rows();
    }

    /**
     * Сохраняет список объектов в БД
     *
     * @return int Количество успешных записей
     */
    public function save_list(): int
    {
        $count = 0;

        $update_data = [];

        foreach ($this->_list as $object) {
            if (isset($object->id)) {
                $update_data[] = $object;
            } else {
                $count += $this->CI->db->insert($this->_table, $this);

                $object->id = $this->CI->db->insert_id();
            }
        }

        if (count($update_data) > 0) {
            $count += $this->CI->db->update_batch(
                $this->_table,
                $update_data,
                $this->_primary_key
            );
        }

        return $count;
    }

    /**
     * Вносит новый объект в список, копируя свойства текущего
     *
     */
    public function add_to_list(): void
    {
        $object = new stdClass();

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
     * @param int|null $id ID объекта
     *
     * @return int Количество успешных удалений
     */
    public function delete(int $id = null): int
    {
        $this->CI->db->delete(
            $this->_table,
            [$this->_primary_key => $id ?? $this->id]
          );

        return $this->CI->db->affected_rows();
    }
}

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
    protected $_table;

    /**
     * Хранилище неизвестных свойств
     *
     * @var array
     */
    protected $_data = [];

    private $_relationship = [
      '_belongs_to',
      '_has_one',
      '_has_many'
    ];

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->db = $this->CI->db;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }

        $vars = get_object_vars($this);

        foreach ($this->_relationship as $rel) {
            if (array_key_exists($rel, $vars)) {
                if (array_key_exists($name, $vars[$rel])) {
                    return $this->_data[$name] = $this->$rel($name);
                }
            }
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
     * Получает объект по ID
     *
     * @param int $id ID объекта
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function get(int $id): bool
    {
        $query = $this->db
            ->where('id', $id)
            ->limit(1)
            ->get($this->_table);

        if ($query->num_rows() == 0) {
            return false;
        }

        $this->_set($query->row());

        return true;
    }

    /**
     * Получает объект по параметрам
     *
     * @param array $attr Набор параметров
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function get_by(array $attr): bool
    {
        $query = $this->db
            ->where($attr)
            ->limit(1)
            ->get($this->_table);

        if ($query->num_rows() == 0) {
            return false;
        }

        $this->_set($query->row());

        return true;
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
            $this->db
                ->where('id', $this->id)
                ->update($this->_table, $this);
        } else {
            $this->db->insert($this->_table, $this);

            $this->id = $this->db->insert_id();
        }

        return $this->db->affected_rows();
    }

    /**
     * Удаляет объект из БД
     *
     * @return int Количество успешных удалений
     */
    public function remove(): int
    {
        $this->db->delete($this->_table, ['id' => $this->id]);

        return $this->db->affected_rows();
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
     * Получает владельца //TODO нормальное описание
     *
     * @param string $name
     *
     * @return object|null
     */
    private function _belongs_to($name): ?object
    {
        $classname = $this->_belongs_to[$name]['class'];
        $foreign_key = $this->_belongs_to[$name]['foreign_key'];

        $query = $this->db
            ->where('id', $this->$foreign_key)
            ->get($classname)
            ->row();

        if (! isset($query)) {
            return null;
        }

        $object = new $classname($query->id);

        return $object;
    }

    /**
     * Получает "имущество" объекта //TODO нормальное описание
     *
     * @param string $name
     *
     * @return object|null
     */
    private function _has_one($name): ?object
    {
        $classname = $this->_has_one[$name]['model'];
        $foreign_key = $this->_has_one[$name]['foreign_key'];

        $query = $this->db
            ->where($foreign_key, $this->id)
            ->get($classname)
            ->row();

        if (! isset($query)) {
            return null;
        }

        $object = new $classname($query->id);

        return $object;
    }

    /**
     * Получает "имущество" объекта //TODO нормальное описание
     *
     * @param string $name
     *
     * @return array
     */
    private function _has_many($name): array
    {
        $classname = $this->_has_many[$name]['class'];
        $foreign_key = $this->_has_many[$name]['foreign_key'];
        $through = $this->_has_many[$name]['through'] ?? null;

        if (isset($through)) {
            $this->db
                ->select("{$foreign_key[1]} AS id")
                ->from($through)
                ->where($foreign_key[0], $this->id);
        } else {
            $this->db
                ->select('id')
                ->from($classname)
                ->where($foreign_key, $this->id);
        }

        $query = $this->db
            ->get()
            ->result();

        foreach ($query as &$row) {
            $row = new $classname($row->id);
        }

        return $query;
    }
}

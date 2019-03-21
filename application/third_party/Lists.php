<?php
namespace ORM;

/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 21.03.2019
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
 * Class Lists
 */
class Lists extends MicroORM
{
    /**
     * Владелец списка
     *
     * @var string
     */
    private $_object;

    /**
     * Название класса
     *
     * @var string
     */
    private $_classname;

    /**
     * Список объектов
     *
     * @var array
     */
    private $_list = [];

    /**
     * @param string $classname
     * @param Entries $object
     *
     * @return void
     */
    public function __construct(string $classname, Entries $object)
    {
        parent::__construct();

        $this->_classname = $classname;
        $this->_object = $object;
    }

    public function select(string $params): Lists
    {
        parent::$_db->select($params);

        return $this;
    }

    public function from(string $params): Lists
    {
        parent::$_db->from($params);

        return $this;
    }

    public function where(string $params, $value = null): Lists
    {
        parent::$_db->where($params, $value);

        return $this;
    }

    public function get(): array
    {
        if ($this->_list) {
            return $this->_list;
        }

        $query = parent::$_db
            ->get()
            ->result();

        $namespace = (new \ReflectionClass($this))->getNamespaceName();
        $classname =  "$namespace\\$this->_classname";

        foreach ($query as &$row) {
            $row = new $classname($row->id);
        }
        unset($row);

        return $this->_list = $query;
    }

    public function first(): ?Entries
    {
        return $this->_object;
    }
}

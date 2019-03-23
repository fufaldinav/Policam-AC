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
     * Объект-владелец
     *
     * @var string
     */
    private $_object;

    /**
     * Модель связи с владелецем
     *
     * @var string
     */
    private $_relation_model;

    /**
     * Тип модели
     *
     * @var string
     */
    private $_relation_type;

    /**
     * Список объектов
     *
     * @var array
     */
    private $_list = [];

    /**
     * @param Entries     $object         Объект-владелец
     * @param array|null  $relation_model Модель связи с владелецем
     * @param string|null $relation_type  Тип модели
     *
     * @return void
     */
    public function __construct(
        Entries $object,
        array $relation_model = null,
        string $relation_type = null
    ) {
        parent::__construct();

        $this->_object = $object;
        $this->_relation_model = $relation_model;
        $this->_relation_type = $relation_type;
    }

    /**
     * Возвращает список объектов
     *
     * @return array Список объектов
     */
    public function get(): array
    {
        if ($this->_list) {
            return $this->_list;
        }

        $relation_type = $this->_relation_type;

        if (isset($relation_type)) {
            return $this->_list = $this->$relation_type();
        }

        return $this->_list;
    }

    /**
     * Возвращает первый объект списка
     *
     * @return Entries|null Объект списка или NULL - список пуст
     */
    public function first(): ?Entries
    {
        if ($this->_list) {
            return $this->_list[0];
        }

        return null;
    }

    /**
     * TODO
     *
     * @param string $params
     * @param mixed|null $value
     *
     * @return Lists
     */
    public function where(string $params, $value = null): Lists
    {
        parent::$_db->where($params, $value);

        return $this;
    }

    /**
     * Получает список объектов, связанных моделью one-to-many
     *
     * @return array Список объектов
     */
    private function _has_many(): array
    {
        $classname = $this->_relation_model['class'];
        $foreign_key = $this->_relation_model['foreign_key'];

        $query = self::$_db
            ->where($foreign_key, $this->_object->id)
            ->get($classname);

        $namespace = (new \ReflectionClass($this->_object))->getNamespaceName();
        $classname =  "$namespace\\$classname";

        return $query->result($classname);
    }

    /**
     * Получает список объектов, связанных моделью many-to-many
     *
     * @return array Список объектов
     */
    private function _with_many(): array
    {
        $classname = $this->_relation_model['class'];
        $own_key = $this->_relation_model['own_key'];
        $their_key = $this->_relation_model['their_key'];
        $through = $this->_relation_model['through'];

        $query = self::$_db
            ->select("$classname.*")
            ->join($classname, "$classname.id = $through.$their_key")
            ->where($own_key, $this->_object->id)
            ->get($through);

        $namespace = (new \ReflectionClass($this->_object))->getNamespaceName();
        $classname =  "$namespace\\$classname";

        return $query->result($classname);
    }
}

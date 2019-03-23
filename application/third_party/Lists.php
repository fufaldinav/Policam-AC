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
    /** @var string Имя списка*/
    private $name;

    /** @var Entries Владелец списка */
    private $owner;

    /** @var string Модель связи */
    private $relation_model;

    /** @var array Коллекция объектов */
    private $collection = [];

    /**
     * @param string       $name  Имя списка, соответствующее таблице в БД
     * @param Entries|null $owner Объект-владелец
     *
     * @return void
     */
    public function __construct(string $name, Entries $owner = null)
    {
        parent::__construct();

        $this->name = $name;

        if (isset($owner)) {
            $this->setOwner($owner);
        }
    }

    /**
     * Возвращает коллекцию объектов
     *
     * @return array Массив объектов
     */
    public function get(): array
    {
        if ($this->collection) {
            return $this->collection;
        }

        if (isset($this->owner) && isset($this->relation_model)) {
            $method = $this->relation_model['method'];
            return $this->collection = $this->$method();
        }

        return $this->collection = $this->db
            ->get($this->name)
            ->result();
    }

    /**
     * Возвращает первый объект из коллекции
     *
     * @return Entries|null Объект или NULL, если список пуст
     */
    public function first(): ?Entries
    {
        if ($this->collection) {
            return $this->collection[0];
        }

        return null;
    }

    /**
     * Поиск по конкретным данным
     *
     * @param string|array $params
     * @param mixed|null   $value
     *
     * @return Lists
     */
    public function where($params, $value = null): Lists
    {
        $this->db->where($params, $value);

        return $this;
    }

    /**
     * Поиск по конкретным данным
     *
     * @param string $column
     * @param array  $values
     *
     * @return Lists
     */
    public function whereIn(string $column, array $values): Lists
    {
        $this->db->where_in($column, $values);

        return $this;
    }

    /**
     * Сортировка результата
     *
     * @param string $params
     *
     * @return Lists
     */
    public function orderBy(string $params): Lists
    {
        $this->db->order_by($params);

        return $this;
    }

    /**
     * TODO
     *
     * @param Entries $owner Объект, владелец списка
     *
     * @return void
     */
    private function setOwner(Entries $owner): void
    {
        $this->owner = $owner;
        $this->relation_model = parent::getRelationModel($this->owner, $this->name);
    }

    /**
     * Получает список объектов, связанных моделью one-to-many
     *
     * @return array Список объектов
     */
    private function getMany(): array
    {
        $classname = $this->relation_model['class'];
        $foreign_key = $this->relation_model['foreign_key'];

        $query = $this->db
            ->where($foreign_key, $this->owner->id)
            ->get($classname);

        $namespace = (new \ReflectionClass($this->owner))->getNamespaceName();
        $classname =  "$namespace\\$classname";

        return $query->result($classname);
    }

    /**
     * Получает список объектов, связанных моделью many-to-many
     *
     * @return array Список объектов
     */
    private function getManyByPivot(): array
    {
        $classname = $this->relation_model['class'];
        $own_key = $this->relation_model['own_key'];
        $their_key = $this->relation_model['their_key'];
        $pivot = $this->relation_model['pivot'];

        $query = $this->db
            ->select("$classname.*")
            ->join($classname, "$classname.id = $pivot.$their_key")
            ->where($own_key, $this->owner->id)
            ->get($pivot);

        $namespace = (new \ReflectionClass($this->owner))->getNamespaceName();
        $classname =  "$namespace\\$classname";

        return $query->result($classname);
    }
}

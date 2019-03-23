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
    /** @var string Объект-владелец */
    private $object;

    /** @var string Модель связи с владелецем */
    private $relation_model;

    /** @var string Вызываемый метод */
    private $relation_method;

    /** @var array Коллекция объектов */
    private $collection = [];

    /**
     * @param Entries     $object          Объект-владелец
     * @param array|null  $relation_model  Модель связи с владелецем
     * @param string|null $relation_method Вызываемый метод
     *
     * @return void
     */
    public function __construct(
        Entries $object,
        array $relation_model = null,
        string $relation_method = null
    ) {
        parent::__construct();

        $this->object = $object;
        $this->relation_model = $relation_model;
        $this->relation_method = $relation_method;
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

        $relation_method = $this->relation_method;

        if (isset($relation_method)) {
            return $this->collection = $this->$relation_method();
        }

        return $this->collection;
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
     * @param string $params
     * @param mixed|null $value
     *
     * @return Lists
     */
    public function where(string $params, $value = null): Lists
    {
        parent::$db->where($params, $value);

        return $this;
    }

    /**
     * Сортировка результата
     *
     * @param string $params
     *
     * @return Lists
     */
    public function order_by(string $params): Lists
    {
        parent::$db->order_by($params);

        return $this;
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

        $query = self::$db
            ->where($foreign_key, $this->object->id)
            ->get($classname);

        $namespace = (new \ReflectionClass($this->object))->getNamespaceName();
        $classname =  "$namespace\\$classname";

        return $query->result($classname);
    }

    /**
     * Получает список объектов, связанных моделью many-to-many
     *
     * @return array Список объектов
     */
    private function getManyByMap(): array
    {
        $classname = $this->relation_model['class'];
        $own_key = $this->relation_model['own_key'];
        $their_key = $this->relation_model['their_key'];
        $mapped_by = $this->relation_model['mapped_by'];

        $query = self::$db
            ->select("$classname.*")
            ->join($classname, "$classname.id = $mapped_by.$their_key")
            ->where($own_key, $this->object->id)
            ->get($mapped_by);

        $namespace = (new \ReflectionClass($this->object))->getNamespaceName();
        $classname =  "$namespace\\$classname";

        return $query->result($classname);
    }
}

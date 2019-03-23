<?php
namespace ORM;

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
 * Class Entries
 */
abstract class Entries extends MicroORM
{
    /**
     * ID объекта
     *
     * @var int
     */
    public $id;

    /**
     * Таблица в БД
     *
     * @var string
     */
    protected $_table;

    /**
     * Типы связей
     *
     * @var array
     */
    private $_relationship_types = [
      '_belongs_to', //many-to-one
      '_has_one',    //one-to-one
      '_has_many',   //one-to-many
      '_with_many'   //many-to-many
    ];

    /**
     * @param mixed $param
     *
     * @return void
     */
    public function __construct($param = null)
    {
        parent::__construct();

        $this->_table = strtolower((new \ReflectionClass($this))->getShortName());

        if (is_numeric($param)) {
            $this->get($param);
        } elseif (is_array($param)) {
            $this->get_by($param);
        }
    }

    /**
     * @param string $name Имя свойства
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_storage)) {
            return $this->_storage[$name];
        }

        $props = get_object_vars($this);

        foreach ($this->_relationship_types as $type) {
            if (array_key_exists($type, $props)) {
                if (array_key_exists($name, $props[$type])) {
                    return $this->_storage[$name] = $this->$type($name);
                }
            }
        }

        return null;
    }

    /**
     * @param string $name Имя свойства
     * @param mixed $value Значение свойства
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->_storage[$name] = $value;
    }

    /**
     * @param string $name Имя свойства
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_storage[$name]);
    }

    /**
     * @param string $name Имя свойства
     *
     * @return void
     */
    public function __unset($name)
    {
        unset($this->_storage[$name]);
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
        $query = parent::$_db
            ->where('id', $id)
            ->limit(1)
            ->get($this->_table);

        if ($query->num_rows() === 0) {
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
        $query = parent::$_db
            ->where($attr)
            ->limit(1)
            ->get($this->_table);

        if ($query->num_rows() === 0) {
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
            throw new \Exception('Argument 1 passed to ' . __METHOD__ . '() must be array or object, ' . gettype($object) . ' given, called in ' . __FILE__ . ' on line ' . __LINE__);
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
            parent::$_db
                ->where('id', $this->id)
                ->update($this->_table, $this);
        } else {
            parent::$_db->insert($this->_table, $this);

            $this->id = parent::$_db->insert_id();
        }

        return parent::$_db->affected_rows();
    }

    /**
     * Удаляет объект из БД
     *
     * @return int Количество успешных удалений
     */
    public function remove(): int
    {
        parent::$_db->delete($this->_table, ['id' => $this->id]);

        return parent::$_db->affected_rows();
    }

    /**
     * Связать объекты
     *
     * @param self $bindable Связываемый объект
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function bind(self $bindable): bool
    {
        $model = $this->_get_relation_model($bindable);

        if ($this->_check_relation($bindable, $model)) {
            return false;
        }

        if (isset($model)) {
            parent::$_db->insert($model['through'], [
                $model['own_key'] => $this->id,
                $model['their_key'] => $bindable->id
            ]);
            return true;
        }

        return false;
    }

    /**
     * Удалить связь объектов
     *
     * @param self $binded Связанный объект
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function unbind(self $binded): bool
    {
        $model = $this->_get_relation_model($binded);

        if (! $this->_check_relation($binded, $model)) {
            return false;
        }

        if (isset($model)) {
            parent::$_db->where([
                $model['own_key'] => $this->id,
                $model['their_key'] => $binded->id
            ]);

            parent::$_db->delete($model['through']);

            if (parent::$_db->affected_rows() > 0) {
                return true;
            }
        }

        return false;
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
     * Получает объект, связанный моделью many-to-one
     *
     * @param string $name Имя свойства текущего объекта, которое будет
     *                     хранить связанный объект
     *
     * @return self|null Связанный объект
     */
    private function _belongs_to(string $name): ?self
    {
        $classname = $this->_belongs_to[$name]['class'];
        $foreign_key = $this->_belongs_to[$name]['foreign_key'];

        $query = parent::$_db
            ->where('id', $this->$foreign_key)
            ->limit(1)
            ->get($classname);

        if ($query->num_rows() === 0) {
            return null;
        }

        $namespace = (new \ReflectionClass($this))->getNamespaceName();
        $classname =  "$namespace\\$classname";

        $object = $query->row(0, $classname);

        return $object;
    }

    /**
     * Получает объект, связанный моделью one-to-one
     *
     * @param string $name Имя свойства текущего объекта, которое будет
     *                     хранить связанный объект
     *
     * @return self|null Связанный объект
     */
    private function _has_one(string $name): ?self
    {
        $classname = $this->_has_one[$name]['model'];
        $foreign_key = $this->_has_one[$name]['foreign_key'];

        $query = parent::$_db
            ->where($foreign_key, $this->id)
            ->limit(1)
            ->get($classname);

        if ($query->num_rows() === 0) {
            return null;
        }

        $namespace = (new \ReflectionClass($this))->getNamespaceName();
        $classname =  "$namespace\\$classname";

        $object = $query->row(0, $classname);

        return $object;
    }

    /**
     * Получает список объектов, связанных моделью one-to-many
     *
     * @param string $name Имя свойства текущего объекта, которое будет
     *                     хранить список связанных объектов
     *
     * @return Lists Список объектов
     */
    private function _has_many(string $name): Lists
    {
        $list = new Lists($this, $this->_has_many[$name], '_has_many');

        return $list;
    }

    /**
     * Получает список объектов, связанных моделью many-to-many
     *
     * @param string $name Имя свойства текущего объекта, которое будет
     *                     хранить список связанных объектов
     *
     * @return Lists Список объектов
     */
    private function _with_many(string $name): Lists
    {
        $list = new Lists($this, $this->_with_many[$name], '_with_many');

        return $list;
    }

    /**
     * Возвращает парамеры связи с классом объекта
     *
     * @param self $object Связываемый объект
     *
     * @return array Параметры связи объектов
     */
    private function _get_relation_model(self $object): array
    {
        $props = get_object_vars($this);

        $classname = (new \ReflectionClass($object))->getShortName();
        $classname = strtolower($classname);

        if (array_key_exists('_with_many', $props)) { //если есть связь у объекта
            foreach ($props['_with_many'] as $rel) {
                if ($rel['class'] === $classname) { //если класс О1 связан с классом О2
                    return $rel;
                }
            }
        }

        throw new \Exception('Relations model no exists');
    }

    /**
     * Проверяет связь с классом объекта, используя модель связи
     *
     * @param self $object Связываемый объект
     * @param array $model Параметры связи
     *
     * @return bool TRUE - объекты связаны, FALSE - связь отсутствует
     */
    private function _check_relation(self $object, array $model): bool
    {
        $query = parent::$_db
            ->where([
              $model['own_key'] => $this->id,
              $model['their_key'] => $object->id
            ])
            ->get($model['through']);

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }
}

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
    /** @var int ID объекта */
    public $id;

    /** @var string Таблица в БД */
    protected $table;

    /** @var array Типы связей */
    private $relationship_types = [
      'belongs_to' => 'getOwner',   //many-to-one
      'has_one' => 'getOne',        //one-to-one
      'has_many' => 'getMany',      //one-to-many
      'with_many' => 'getManyByMap' //many-to-many
    ];

    /**
     * @param mixed $param Параметры создаваемого объекта
     *
     * @return void
     */
    public function __construct($param = null)
    {
        parent::__construct();

        $this->table = strtolower((new \ReflectionClass($this))->getShortName());

        if (is_numeric($param)) {
            $this->get($param);
        } elseif (is_array($param)) {
            $this->getBy($param);
        }
    }

    /**
     * @param string $name Имя свойства
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->storage)) {
            return $this->storage[$name];
        }

        $props = get_object_vars($this);

        foreach ($this->relationship_types as $type => $method) {
            if (array_key_exists($type, $props)) {
                if (array_key_exists($name, $props[$type])) {
                    return $this->storage[$name] = $this->$method($name);
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
        $this->storage[$name] = $value;
    }

    /**
     * @param string $name Имя свойства
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->storage[$name]);
    }

    /**
     * @param string $name Имя свойства
     *
     * @return void
     */
    public function __unset($name)
    {
        unset($this->storage[$name]);
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
        $query = parent::$db
            ->where('id', $id)
            ->limit(1)
            ->get($this->table);

        if ($query->num_rows() === 0) {
            return false;
        }

        foreach ($query->row() as $key => $value) {
            $this->$key = $value;
        }

        return true;
    }

    /**
     * Получает объект по параметрам
     *
     * @param array $attr Набор параметров
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function getBy(array $attr): bool
    {
        $query = parent::$db
            ->where($attr)
            ->limit(1)
            ->get($this->table);

        if ($query->num_rows() === 0) {
            return false;
        }

        foreach ($query->row() as $key => $value) {
            $this->$key = $value;
        }

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
            parent::$db
                ->where('id', $this->id)
                ->update($this->table, $this);
        } else {
            parent::$db->insert($this->table, $this);

            $this->id = parent::$db->insert_id();
        }

        return parent::$db->affected_rows();
    }

    /**
     * Удаляет объект из БД
     *
     * @return int Количество успешных удалений
     */
    public function remove(): int
    {
        parent::$db->delete($this->table, ['id' => $this->id]);

        return parent::$db->affected_rows();
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
        $model = $this->getRelationModel($bindable);

        if ($this->checkRelation($bindable, $model)) {
            return false;
        }

        if (isset($model)) {
            parent::$db->insert($model['mapped_by'], [
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
        $model = $this->getRelationModel($binded);

        if (! $this->checkRelation($binded, $model)) {
            return false;
        }

        if (isset($model)) {
            parent::$db->where([
                $model['own_key'] => $this->id,
                $model['their_key'] => $binded->id
            ]);

            parent::$db->delete($model['mapped_by']);

            if (parent::$db->affected_rows() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Получает объект, связанный моделью many-to-one
     *
     * @param string $name Имя свойства текущего объекта, которое будет
     *                     хранить связанный объект
     *
     * @return self|null Связанный объект
     */
    private function getOwner(string $name): ?self
    {
        $classname = $this->belongs_to[$name]['class'];
        $foreign_key = $this->belongs_to[$name]['foreign_key'];

        $query = parent::$db
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
    private function getOne(string $name): ?self
    {
        $classname = $this->has_one[$name]['model'];
        $foreign_key = $this->has_one[$name]['foreign_key'];

        $query = parent::$db
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
    private function getMany(string $name): Lists
    {
        $list = new Lists($this, $this->has_many[$name], __FUNCTION__);

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
    private function getManyByMap(string $name): Lists
    {
        $list = new Lists($this, $this->with_many[$name], __FUNCTION__);

        return $list;
    }

    /**
     * Возвращает парамеры связи с классом объекта
     *
     * @param self $object Связываемый объект
     *
     * @return array Параметры связи объектов
     */
    private function getRelationModel(self $object): array
    {
        $props = get_object_vars($this);

        $classname = (new \ReflectionClass($object))->getShortName();
        $classname = strtolower($classname);

        if (array_key_exists('with_many', $props)) { //если есть связь у объекта
            foreach ($props['with_many'] as $rel) {
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
    private function checkRelation(self $object, array $model): bool
    {
        $query = parent::$db
            ->where([
              $model['own_key'] => $this->id,
              $model['their_key'] => $object->id
            ])
            ->get($model['mapped_by']);

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }
}

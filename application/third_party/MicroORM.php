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
 * Class MicroORM
 */
abstract class MicroORM
{
    /** @var object Объект БД */
    protected $db;

    /** @var array Модели связей */
    private static $relations_models = [
        'belongs_to' => [
            'name' => 'belongs_to',
            'method' => 'getOwner'
        ],           //many-to-one
        'has_one' => [
            'name' => 'has_one',
            'method' => 'getOne'
        ],                //one-to-one
        'has_many' => [
            'name' => 'has_many',
            'method' => 'getMany'
        ],              //one-to-many
        'belongs_to_many' => [
            'name' => 'belongs_to_many',
            'method' => 'getManyByPivot'
        ] //many-to-many
    ];

    /** @var array Хранилище неизвестных свойств */
    protected $storage = [];

    /** @return void */
    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->database();
        $this->db = $CI->db;
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
     * TODO
     *
     * @return array
     */
    final protected static function getRelationsModels(): array
    {
        return self::$relations_models;
    }

    /**
     * TODO
     *
     * @param self        $object Объект, в котором ищем связь
     * @param string|self $target Имя связываемого класса или объект
     *
     * @return array|null Модель связи или NULL, если связь не найдена
     */
    final protected static function getRelationModel(self $object, $target): ?array
    {
        if (is_object($target)) {
            $classname = (new \ReflectionClass($target))->getShortName();
            $classname = strtolower($classname);
        } elseif (is_string($target)) {
            $classname = $target;
        } else {
            return null;
        }

        $props = get_object_vars($object);

        foreach (self::$relations_models as $name => $model) {
            if (array_key_exists($name, $props)) {
                if (array_key_exists($classname, $props[$name])) {
                    return array_merge($model, $props[$name][$classname]);
                }
            }
        }

        return null;
    }
}

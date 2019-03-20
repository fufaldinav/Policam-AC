<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 17.03.2019
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
 * Class Ac
 */
class Ac
{
    /**
     * Суперобъект Codeigniter
     *
     * @var object
     */
    protected static $_CI;

    /**
     * Хранилище неизвестных свойств
     *
     * @var array
     */
    protected $_data = [];

    /**
     * @return void
     */
    public function __construct()
    {
        self::$_CI =& get_instance();

        self::$_CI->config->load('ac', true);

        include_once APPPATH . 'third_party/MicroORM.php';
    }

    /**
     * @param string $name Имя свойства
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
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
        $this->_data[$name] = $value;
    }

    /**
     * @param string $name Имя свойства
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * @param string $name Имя свойства
     *
     * @return void
     */
    public function __unset($name)
    {
        unset($this->_data[$name]);
    }

    /**
     * Загрузка моделей
     *
     * @param string $model Имя модели
     * @param string $name  Альтернативное имя
     */
    public function model($model, $name = null)
    {
        $name = $name ?? $model;

        self::$_CI->load->model("ac/{$model}_model", $name);

        $this->$name = self::$_CI->$name;
    }

    /**
     * Загрузка классов
     *
     * @param string $class Имя класса
     */
    public function load($class)
    {
        include_once APPPATH . "third_party/$class.php";
    }
}

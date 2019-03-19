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
    protected $CI;

    /**
     * Хранилище неизвестных свойств
     *
     * @var array
     */
    protected $_data = [];

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->config->load('ac', true);

        include_once APPPATH . 'third_party/MicroORM.php';
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
     * Упрощенная загрузка моделей
     *
     * @param string $model Имя модели
     * @param string $name  Альтернативное имя
     */
    public function load($model, $name = null)
    {
        $name = $name ?? $model;

        $this->CI->load->model("ac/{$model}_model", $name);

        $this->$name = $this->CI->$name;
    }

    /**
     * Упрощенная загрузка классов
     *
     * @param string $class Имя модели
     */
    public function class($class)
    {
        include_once APPPATH . "third_party/$class.php";
    }
}

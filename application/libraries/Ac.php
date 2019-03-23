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
    protected $storage = [];

    /**
     * @return void
     */
    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->config->load('ac', true);

        $this->load(['MicroORM', 'Entries', 'Lists']);
    }

    /**
     * @param string $name Имя свойства
     *
     * @return mixed|null
     */
    public function __get(string $name)
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
    public function __set(string $name, $value): void
    {
        $this->storage[$name] = $value;
    }

    /**
     * @param string $name Имя свойства
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->storage[$name]);
    }

    /**
     * @param string $name Имя свойства
     *
     * @return void
     */
    public function __unset(string $name): void
    {
        unset($this->storage[$name]);
    }

    /**
     * Загрузка классов
     *
     * @param string $class Имя класса
     */
    public function load($class): void
    {
        if (is_array($class)) {
            foreach ($class as $value) {
                require_once APPPATH . "third_party/$value.php";
            }
        } elseif (is_string($class)) {
            require_once APPPATH . "third_party/$class.php";
        }
    }
}

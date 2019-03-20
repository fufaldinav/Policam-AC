<?php
namespace Orm;

/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 20.03.2019
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
 * Class User_events
 */
class User_events extends MicroORM
{
    /**
     * @var array
     */
    protected $_belongs_to = [
      'user' => [
        'class' => 'users',
        'foreign_key' => 'user_id'
      ]
    ];

    /**
     * Пользователь, который вызвал событие
     *
     * @var int
     */
    public $user_id;

    /**
     * Тип события
     *
     * @var int
     */
    public $type;

    /**
     * Описание события
     *
     * @var string
     */
    public $description;

    /**
     * Время события
     *
     * @var int
     */
    public $time;

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
}

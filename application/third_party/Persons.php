<?php
namespace Orm;

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
 * Class Persons
 */
class Persons extends Objects
{
    /**
     * @var array
     */
    protected $_has_many = [
      'cards' => [
        'class' => 'cards',
        'foreign_key' => 'person_id'
      ],
      'photos' => [
        'class' => 'photos',
        'foreign_key' => 'person_id'
      ],
      'divisions' => [
        'class' => 'divisions',
        'foreign_key' => [
          'person_id',
          'div_id'
        ],
        'through' => 'persons_divisions'
      ],
      'users' => [
        'class' => 'users',
        'foreign_key' => [
          'person_id',
          'user_id'
        ],
        'through' => 'persons_users'
      ]
    ];

    /**
     * Фамилия
     *
     * @var string
     */
    public $f;

    /**
     * Имя
     *
     * @var string
     */
    public $i;

    /**
     * Отвество
     *
     * @var string
     */
    public $o = null;

    /**
     * Тип/должность человека
     *
     * @var int
     */
    public $type = 1;

    /**
     * Дата рождения
     *
     * @var string
     */
    public $birthday;

    /**
     * Адрес
     *
     * @var string
     */
    public $address = null;

    /**
     * Номер телефона
     *
     * @var string
     */
    public $phone = null;
}

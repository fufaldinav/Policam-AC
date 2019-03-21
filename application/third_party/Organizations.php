<?php
namespace ORM;

/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 19.03.2019
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
 * Class Organizations
 */
class Organizations extends Entries
{
    /**
     * @var array
     */
    protected $_has_many = [
      'divisions' => [
        'class' => 'divisions',
        'foreign_key' => 'org_id'
      ],
      'controllers' => [
        'class' => 'controllers',
        'foreign_key' => 'org_id'
      ]
    ];

    /**
     * @var array
     */
    protected $_with_many = [
      'users' => [
        'class' => 'users',
        'own_key' => 'org_id',
        'their_key' => 'user_id',
        'through' => 'organizations_users'
      ]
    ];

    /**
     * Название организации
     *
     * @var string
     */
    public $name;

    /**
     * Адрес организации
     *
     * @var string
     */
    public $address = null;

    /**
     * Тип организации
     *
     * @var int
     */
    public $type = 1;
}

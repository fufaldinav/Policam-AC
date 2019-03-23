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
    /** @var array Связи one_to_many */
    protected $has_many = [
      'divisions' => [
        'class' => 'divisions',
        'foreign_key' => 'org_id'
      ],
      'controllers' => [
        'class' => 'controllers',
        'foreign_key' => 'org_id'
      ]
    ];

    /** @var array Связи many_to_many */
    protected $with_many = [
      'users' => [
        'class' => 'users',
        'own_key' => 'org_id',
        'their_key' => 'user_id',
        'mapped_by' => 'organizations_users'
      ]
    ];

    /** @var string Название организации */
    public $name;

    /** @var string Адрес организации */
    public $address = null;

    /** @var int Тип организации */
    public $type = 1;
}

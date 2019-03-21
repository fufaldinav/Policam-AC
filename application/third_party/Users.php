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
 * Class Users
 */
class Users extends Entries
{
    /**
     * @var array
     */
    protected $_has_many = [
      'tokens' => [
        'class' => 'tokens',
        'foreign_key' => 'user_id'
      ]
    ];

    /**
     * @var array
     */
    protected $_with_many = [
      'organizations' => [
        'class' => 'organizations',
        'own_key' => 'user_id',
        'their_key' => 'org_id',
        'through' => 'organizations_users'
      ],
      'persons' => [
        'class' => 'persons',
        'own_key' => 'user_id',
        'their_key' => 'person_id',
        'through' => 'persons_users'
      ]
    ];
}

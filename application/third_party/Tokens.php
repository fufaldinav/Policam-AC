<?php
namespace Orm;

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
 * Class Tokens
 */
class Tokens extends MicroORM
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
     * Пользователь, которому принадлежит токен
     *
     * @var int
     */
    public $user_id;

    /**
     * Токен, полученный от сервера FCM
     *
     * @var string
     */
    public $token;
}

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
 * Class Tokens
 */
class Tokens extends Entries
{
    /** @var array Связи many_to_one */
    protected $belongs_to = [
      'user' => [
        'class' => 'users',
        'foreign_key' => 'user_id'
      ]
    ];

    /** @var int Пользователь, которому принадлежит токен */
    public $user_id;

    /** @var string Токен, полученный от сервера FCM */
    public $token;
}

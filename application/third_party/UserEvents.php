<?php
namespace ORM;

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
 * Class UserEvents
 */
class UserEvents extends Entries
{
    /** @var array Связи many_to_one */
    protected $belongs_to = [
      'user' => [
        'class' => 'users',
        'foreign_key' => 'user_id'
      ]
    ];

    /** @var int Пользователь, который вызвал событие */
    public $user_id;

    /** @var int Тип события */
    public $type;

    /** @var string Описание события */
    public $description;

    /** @var int Время события */
    public $time;
}

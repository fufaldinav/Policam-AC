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
 * Class Events
 */
class Events extends Entries
{
    /** @var array Связи many_to_one */
    protected $belongs_to = [
      'controller' => [
        'class' => 'controllers',
        'foreign_key' => 'controller_id'
      ],
      'card' => [
        'class' => 'cards',
        'foreign_key' => 'card_id'
      ]
    ];

    /** @var int Контроллер, от которого пришло событие */
    public $controller_id;

    /** @var int Тип события */
    public $event;

    /** @var int Флаг события */
    public $flag;

    /** @var int Время события на контроллере */
    public $time;

    /** @var int Время получения события сервером */
    public $server_time;

    /** @var int Карта, вызвавшая событие */
    public $card_id;
}

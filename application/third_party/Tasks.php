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
 * Class Tasks
 */
class Tasks extends Entries
{
    /** @var array Связи many_to_one */
    protected $belongs_to = [
      'controller' => [
        'class' => 'controllers',
        'foreign_key' => 'controller_id'
      ]
    ];

    /** @var int ID задания */
    public $task_id;

    /** @var int Контроллер, которому предназначено задание */
    public $controller_id;

    /** @var string Сообщение в формате JSON */
    public $json;

    /** @var int Время формирования сообщения */
    public $time;
}

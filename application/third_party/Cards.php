<?php
namespace ORM;

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
 * Class Cards
 */
class Cards extends Entries
{
    /** @var array Связи many_to_one */
    protected $belongs_to = [
      'person' => [
        'class' => 'persons',
        'foreign_key' => 'person_id'
      ]
    ];

    /** @var array Связи one_to_many */
    protected $has_many = [
      'events' => [
        'class' => 'events',
        'foreign_key' => 'card_id'
      ]
    ];

    /** @var string Код карты */
    public $wiegand;

    /** @var int Время последней попытки доступа */
    public $last_conn;

    /** @var int Контроллер, на котором была совершена попытка доступа */
    public $controller_id;

    /** @var int Владелец карты */
    public $person_id = 0;

    /** @var int Признак активированности карты */
    public $active = 1;
}

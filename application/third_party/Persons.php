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
 * Class Persons
 */
class Persons extends Entries
{
    /** @var array Связи one_to_many */
    protected $has_many = [
      'cards' => [
        'class' => 'cards',
        'foreign_key' => 'person_id'
      ],
      'photos' => [
        'class' => 'photos',
        'foreign_key' => 'person_id'
      ]
    ];

    /** @var array Связи many_to_many */
    protected $with_many = [
      'divisions' => [
        'class' => 'divisions',
        'own_key' => 'person_id',
        'their_key' =>   'div_id',
        'mapped_by' => 'persons_divisions'
      ],
      'users' => [
        'class' => 'users',
        'own_key' => 'person_id',
        'their_key' =>   'user_id',
        'mapped_by' => 'persons_users'
      ]
    ];

    /** @var string Фамилия */
    public $f;

    /** @var string Имя */
    public $i;

    /** @var string Отчество */
    public $o = null;

    /** @var int Тип/должность человека */
    public $type = 1;

    /** @var string Дата рождения */
    public $birthday;

    /** @var string Адрес */
    public $address = null;

    /** @var string Номер телефона */
    public $phone = null;
}

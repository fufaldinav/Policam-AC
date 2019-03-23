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
 * Class Divisions
 */
class Divisions extends Entries
{
    /** @var array Связи many_to_one */
    protected $belongs_to = [
      'organization' => [
        'class' => 'organizations',
        'foreign_key' => 'org_id'
      ]
    ];

    /** @var array Связи many_to_many */
    protected $with_many = [
      'persons' => [
        'class' => 'persons',
        'own_key' => 'div_id',
        'their_key' => 'person_id',
        'mapped_by' => 'persons_divisions'
      ]
    ];

    /** @var string Название подразделения */
    public $name;

    /** @var int Организация, которой принадлежит подразделение */
    public $org_id;

    /** @var int Тип подразделения */
    public $type = 1;
}

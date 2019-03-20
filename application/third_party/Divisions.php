<?php
namespace Orm;

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
class Divisions extends MicroORM
{
    /**
     * @var array
     */
    protected $_belongs_to = [
      'organization' => [
        'class' => 'organizations',
        'foreign_key' => 'org_id'
      ]
    ];

    /**
     * @var array
     */
    protected $_has_many = [
      'persons' => [
        'class' => 'persons',
        'foreign_key' => [
          'div_id',
          'person_id'
        ],
        'through' => 'persons_divisions'
      ]
    ];

    /**
     * Название подразделения
     *
     * @var string
     */
    public $name;

    /**
     * Организация, которой принадлежит подразделение
     *
     * @var int
     */
    public $org_id;

    /**
     * Тип подразделения
     *
     * @var int
     */
    public $type = 1;
}

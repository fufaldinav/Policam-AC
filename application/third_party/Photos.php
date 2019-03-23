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
 * Class Photos
 */
class Photos extends Entries
{
    /** @var array Связи many_to_one */
    protected $belongs_to = [
      'person' => [
        'class' => 'persons',
        'foreign_key' => 'person_id'
      ]
    ];

    /** @var string MD5 хэш фотографии */
    public $hash;

    /** @var int Человек, которому принадлежит фотография */
    public $person_id = null;

    /** @var int Время сохранения фотографии */
    public $time;
}

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
    /**
     * @var array
     */
    protected $_belongs_to = [
      'person' => [
        'class' => 'persons',
        'foreign_key' => 'person_id'
      ]
    ];

    /**
     * MD5 хэш фотографии
     *
     * @var string
     */
    public $hash;

    /**
     * Человек, которому принадлежит фотография
     *
     * @var int
     */
    public $person_id = null;

    /**
     * Время сохранения фотографии
     *
     * @var int
     */
    public $time;

    /**
     * Получим старые фотографии (на удаление)
     *
     * @return array
     */
    public static function get_old(): array
    {
        $query = parent::$_db
            ->where('person_id', null)
            ->where('time <', now() - 86400)
            ->get('photos')
            ->result();

        $classname = self::class;

        foreach ($query as &$row) {
            $row = new $classname($row->id);
        }
        unset($row);

        return $query;
    }
}

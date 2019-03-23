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

    /**
     * Получим старые фотографии (на удаление)
     *
     * @return array Список фотографий на удаление
     */
    public static function get_old(): array
    {
        $db =& get_instance()->db;

        $query = $db
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

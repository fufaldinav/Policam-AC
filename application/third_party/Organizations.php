<?php
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
 * Class Organizations
 */
class Organizations extends MicroORM
{
    protected $_has_many = [
      'divisions' => [
        'class' => 'divisions',
        'foreign_key' => 'org_id'
      ],
      'users' => [
        'class' => 'users',
        'foreign_key' => [
          'org_id',
          'user_id'
        ],
        'through' => 'organizations_users'
      ]
    ];

    /**
     * Название организации
     *
     * @var string
     */
    public $name;

    /**
     * Адрес организации
     *
     * @var string
     */
    public $address = null;

    /**
     * Тип организации
     *
     * @var int
     */
    public $type = 1;

    public function __construct($param = null)
    {
        parent::__construct();

        $this->_table = strtolower(get_class($this));

        if (is_numeric($param)) {
            $this->get($param);
        } elseif (is_array($param)) {
            $this->get_by($param);
        }
    }
}

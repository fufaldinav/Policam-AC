<?php
namespace Orm;
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
 * Class Users
 */
class Users extends MicroORM
{
    protected $_has_many = [
      'tokens' => [
        'class' => 'tokens',
        'foreign_key' => 'user_id'
      ],
      'organizations' => [
        'class' => 'organizations',
        'foreign_key' => [
          'user_id',
          'org_id'
        ],
        'through' => 'organizations_users'
      ],
      'persons' => [
        'class' => 'persons',
        'foreign_key' => [
          'user_id',
          'person_id'
        ],
        'through' => 'persons_users'
      ]
    ];

    public function __construct($param = null)
    {
        parent::__construct();

        $this->_table = strtolower((new \ReflectionClass($this))->getShortName());

        if (is_numeric($param)) {
            $this->get($param);
        } elseif (is_array($param)) {
            $this->get_by($param);
        }
    }
}

<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 17.03.2019
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
 * Class Users_events Model
 */
class Users_events_model extends MY_Model
{
    /**
     * Пользователь, от которого пришло сообщение
     *
     * @var int
     */
    public $user_id;

    /**
     * Тип сообщения
     *
     * @var int
     */
    public $type;

    /**
     * Описание сообщения
     *
     * @var string
     */
    public $description;

    /**
     * Время сообщения
     *
     * @var int
     */
    public $time;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'users_events';
        $this->_foreing_key = 'user_id';
    }
}

<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 14.03.2019
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
 * Class Token Model
 */
class Token_model extends MY_Model
{
    /**
     * Пользователь, которому принадлежит токен
     *
     * @var int
     */
    public $user_id;

    /**
     * Токен, полученный от сервера FCM
     *
     * @var string
     */
    public $token;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'tokens';
        $this->_foreing_key = 'user_id';
    }
}

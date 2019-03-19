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
 * Class Tasks
 */
class Tasks extends MicroORM
{
    protected $_belongs_to = [
      'controller' => [
        'class' => 'controllers',
        'foreign_key' => 'controller_id'
      ]
    ];

    /**
     * Контроллер, которому предназначено задание
     *
     * @var int
     */
    public $controller_id;

    /**
     * Сообщение в формате JSON
     *
     * @var string
     */
    public $json;

    /**
     * Время формирования сообщения
     *
     * @var int
     */
    public $time;

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

    /**
     * Сохраняет объект в БД
     *
     * @return int Количество успешных записей
     */
    public function save(): int
    {
        $query = $this->db
            ->where('id', $this->id)
            ->get($this->_table);

        if ($query->num_rows > 0) {
            $this->db
                ->where('id', $this->id)
                ->update($this->_table, $this);
        } else {
            $this->db->insert($this->_table, $this);

            $this->id = $this->db->insert_id();
        }


        return $this->db->affected_rows();
    }
}

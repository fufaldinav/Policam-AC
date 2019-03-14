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
class Token_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    /**
     * Получает токен по ключу
     *
     * @param string $token_key Ключ
     *
     * @return object|null Токен
     */
    public function get(string $token_key): ?object
    {
        $query = $this->db
            ->where('token', $token_key)
            ->get('tokens');

        return $query->row();
    }

    /**
     * Получает все токены пользователя
     *
     * @param int|null $user_id ID пользователя
     *
     * @return object[] Массив с токенами или пустой массив
     */
    public function get_list(int $user_id = null): array
    {
        if (isset($user_id)) {
            $this->db->where('user_id', $user_id);
        }
        $query = $this->db->get('tokens');

        return $query->result();
    }

    /**
     * Добавляет токен
     *
     * @param int    $user_id   ID пользователя
     * @param string $token_key Токен
     *
     * @return int ID токена
     */
    public function add(int $user_id, string $token_key): int
    {
        $this->db->insert('tokens', [
            'user_id' => $user_id,
            'token' => $token_key
        ]);

        return $this->db->insert_id();
    }

    /**
     * Удаление токена
     *
     * @param string $token_key Ключ
     *
     * @return int Количество успешных удалений
     */
    public function delete(string $token_key): int
    {
        $this->db->delete('users_tokens', ['token' => $token_key]);

        return $this->db->affected_rows();
    }
}

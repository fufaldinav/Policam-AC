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
 * Class Notificator
 * @property Person_model $person
 * @property Photo_model $photo
 * @property Token_model $token
 */
class Notificator extends Ac
{
    /**
     * Адрес сервера FCM
     *
     * @var string
     */
    private $_fcm_url;

    /**
     * Ключ сервера
     *
     * @var string
     */
    private $_server_key;

    /**
     * Параметры уведомления
     *
     * @var string
     */
    private $_notification;

    public function __construct()
    {
        parent::__construct();

        $this->_fcm_url = $this->CI->config->item('fcm_url', 'ac');
        $this->_server_key = $this->CI->config->item('server_key', 'ac');
    }

    /**
     * Генерирует уведомление
     *
     * @param int $person_id ID человека
     * @param int $event_id  ID события
     *
     * @return mixed[] Параметры уведомления
     */
    public function generate(int $person_id, int $event_id): array
    {
        $this->CI->lang->load('ac');

        $this->CI->load->helper('language');

        switch ($event_id) {
            case 4: //вход
                $event = lang('entrace');
                break;

            case 5: //выход
                $event = lang('exit');
                break;

            default:
                return [];
        }

        $this->CI->load->helper('url');

        $this->model('person');

        $this->person->get($person_id);

        $this->model('photos');

        $photo = $this->photos->get_by('person_id', $this->person->id);

        return $this->_notification = [
            'title' => $event,
            'body' => "{$this->person->f} {$this->person->i}",
            'icon' => ($photo) ? ("https://" . $_SERVER['HTTP_HOST'] . "/img/ac/s/{$this->photos->id}.jpg") : "",
            'click_action' => base_url('/')
        ];
    }

    /**
     * Отправляет уведомление
     *
     * @param array|null $notification Параметры уведомления
     * @param int|null   $user_id      ID пользователя, по-умолчанию не указан,
     *                                 тогда будет отправлено всем пользователям
     *
     * @return string Ответ на запрос
     */
    public function send(array $notification = null, int $user_id = null): string
    {
        $notification = $notification ?? $this->_notification;

        $registration_ids = [];

        $this->model('token');

        $tokens = $this->token->get_list($user_id);

        foreach ($tokens as $token) {
            $registration_ids[] = $token->token;
        }

        $request_body = [
            'notification' => $this->_notification,
            'registration_ids' => $registration_ids
        ];
        $fields = json_encode($request_body);

        $request_headers = [
            "Content-Type: application/json",
            "Authorization: key=$this->_server_key",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_fcm_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

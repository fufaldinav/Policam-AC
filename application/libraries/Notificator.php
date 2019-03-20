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
 *
 * @property Logger $logger
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

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->_fcm_url = $this->_CI->config->item('fcm_url', 'ac');
        $this->_server_key = $this->_CI->config->item('server_key', 'ac');
    }

    /**
     * Генерирует уведомление
     *
     * @param int $person_id ID человека
     * @param int $event_id  ID события
     *
     * @return mixed[]|null Параметры уведомления или NULL - неподходящее событие
     */
    public function generate(int $person_id, int $event_id): ?array
    {
        $this->_CI->lang->load('ac');

        $this->_CI->load->helper('language');

        switch ($event_id) {
            case 4: //вход
                $event = lang('entrace');
                break;

            case 5: //выход
                $event = lang('exit');
                break;

            default:
                return null;
        }

        $this->load('Persons', 'Photos');

        $this->_CI->load->helper('url');

        $person = new \Orm\Persons($person_id);

        $photo = $person->first('photos');

        $photo_url = "https://{$_SERVER['HTTP_HOST']}/img/ac/s/" . ($photo->id ?? 0) . ".jpg";

        return $this->_notification = [
            'title' => $event,
            'body' => "{$person->f} {$person->i}",
            'icon' => $photo_url,
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
     * @return void
     */
    public function send(array $notification = null, int $user_id = null): void
    {
        $this->load('Tokens');

        $this->_CI->load->library('logger');

        $notification = $notification ?? $this->_notification;

        $registration_ids = [];

        $user = new \Orm\Users($user_id);

        $tokens = $user->tokens;

        foreach ($tokens as $token) {
            $registration_ids[] = $token->token;
        }

        $request_body = [
            'notification' => $notification,
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

        $this->_CI->logger->add('push', "USER: $user->id || $response");
        $this->_CI->logger->write();
    }
}

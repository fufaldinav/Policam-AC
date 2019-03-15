<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 07.03.2019
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
 * Class Notification Model
 * @property Person_model $person
 * @property Photo_model $photo
 * @property Token_model $token
 */
class Notification_model extends CI_Model
{
    /**
     * Адрес сервера FCM
     *
     * @var string $fcm_url
     */
    private $fcm_url;

    /**
     * Ключ сервера
     *
     * @var string $server_key
     */
    private $server_key;

    public function __construct()
    {
        parent::__construct();

        $this->config->load('ac', true);

        $this->load->database();

        $this->load->model('ac/person_model', 'person');
        $this->load->model('ac/photo_model', 'photo');
        $this->load->model('ac/token_model', 'token');

        $this->fcm_url = $this->config->item('fcm_url', 'ac');
        $this->server_key = $this->config->item('server_key', 'ac');
    }

    /**
     * Проверяет подписки
     *
     * @param int      $person_id ID человека
     * @param int|null $user_id   ID пользователя
     *
     * @return array Список подписок
     */
    public function check_subscription(int $person_id, int $user_id = null): array
    {
        if (isset($user_id)) {
            $this->db->where('user_id', $user_id);
        }

        $query = $this->db
            ->where('person_id', $person_id)
            ->get('persons_users');

        return $query->result();
    }

    /**
     * Генерирует уведомление
     *
     * @param int $person_id ID человека
     * @param int $event_id  ID события
     *
     * @return array Параметры уведомления
     */
    public function generate(int $person_id, int $event_id): array
    {
        $this->lang->load('ac');

        $this->load->helper('language');

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

        $this->load->helper('url');

        $this->person->get($person_id);
        $photo = $this->photo->get_by_person($this->person->id);

        $notification = [
            'title' => $event,
            'body' => "{$this->person->f} {$this->person->i}",
            'icon' => (isset($photo)) ? ("https://" . $_SERVER['HTTP_HOST'] . "/img/ac/s/$photo->id.jpg") : "",
            'click_action' => base_url('/')
        ];

        return $notification;
    }
    /**
     * Отправляет уведомление
     *
     * @param array    $notification Параметры уведомления
     * @param int|null $user_id      ID пользователя
     *
     * @return string Ответ на запрос
     */
    public function send(array $notification, int $user_id = null): string
    {
        $registration_ids = [];

        $tokens = $this->token->get_list($user_id);

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
            "Authorization: key=$this->server_key",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->fcm_url);
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

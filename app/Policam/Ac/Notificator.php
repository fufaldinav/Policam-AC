<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 29.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */

namespace app\Policam\Ac;

use App;

class Notificator
{
    /** @var string Адрес сервера FCM */
    private $fcm_url;

    /** @var string Ключ сервера */
    private $server_key;

    /** @var string Параметры уведомления */
    private $notification_body;

    public function __construct()
    {
        $this->fcm_url = config('ac.fcm.fcm_url');
        $this->server_key = config('ac.fcm.server_key');
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
        switch ($event_id) {
            case 4: //вход
                $event = __('ac/common.entrace');
                break;

            case 5: //выход
                $event = __('ac/common.exit');
                break;

            default:
                return null;
        }

        $person = App\Person::find($person_id);

        $photo = $person->photos()->first();

        $photo_url = "https://{$_SERVER['HTTP_HOST']}/img/ac/s/" . ($photo->id ?? 0) . ".jpg";

        return $this->notification_body = [
            'title' => $event,
            'body' => "{$person->f} {$person->i}",
            'icon' => $photo_url,
            'click_action' => url('/')
        ];
    }

    /**
     * Отправляет уведомление
     *
     * @param array|null $notification_body Параметры уведомления
     * @param int|null $user_id ID пользователя, по-умолчанию не указан,
     *                            тогда будет отправлено всем пользователям
     *
     * @return void
     * @throws \Exception
     */
    public function send(array $notification_body = null, int $user_id = null): void
    {
        $notification_body = $notification_body ?? $this->notification_body;

        $registration_ids = [];

        $user = App\User::find($user_id);

        $notification = new App\Notification();
        $notification->hash = bin2hex(random_bytes(16));
        $notification->user_id = $user->id;

        $notification_body['click_action'] = url("users/notification/$notification->hash");

        $tokens = $user->tokens;

        foreach ($tokens as $token) {
            $registration_ids[] = $token->token;
        }

        $request_body = [
            'notification' => $notification_body,
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

        if ($response) {
            $notification->save();
        }

        $logger = new Logger();
        $logger->add('push', "USER: $user->id || $response");
        $logger->write();
    }
}

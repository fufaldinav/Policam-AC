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
use Carbon\Carbon;

class Notificator
{
    /** @var string Адрес сервера FCM */
    private $fcm_url;

    /** @var string Ключ сервера */
    private $server_key;

    /** @var array Параметры уведомления */
    private $notification_body = [];

    /** @var App\Controller */
    private $controller;

    /** @var App\Event */
    private $event;

    /** @var App\Person */
    private $person;

    public function __construct()
    {
        $this->fcm_url = config('ac.fcm.fcm_url');
        $this->server_key = config('ac.fcm.server_key');
    }

    /**
     * Обрабатывает событие с контроллера
     *
     * @param App\Event $event
     *
     * @return void
     * @throws \Exception
     */
    public function handleEvent(App\Event $event)
    {
        if ($event->event != 39 || $event->flag != 775) {
            return null;
        }

        $pass_time = Carbon::createFromTimeString($event->time);

        $this->event = App\Event::whereIn('event', [4, 5])
            ->where('time', '<=', $pass_time->toDateTimeString())
            ->orderBy('time', 'DESC')
            ->first();

        if (!$this->event) {
            return null;
        }

        $open_time = Carbon::createFromTimeString($this->event->time);

        if ($open_time->diffInSeconds($pass_time) > 5) {
            return null;
        }

        $this->controller = App\Controller::find($this->event->controller_id);

        $card = App\Card::find($this->event->card_id);

        $this->person = $card->person;

        $this->generate();

        if (!$this->notification_body) {
            return null;
        }

        $subs = $this->person->users;

        foreach ($subs as $sub) {
            $this->send($sub->id);
        }

    }

    /**
     * Генерирует уведомление
     *
     * @return mixed[]|null Параметры уведомления или NULL - неподходящее событие
     */
    private function generate(): ?array
    {
        switch ($this->event->event) {
            case 4: //вход
                $event = __('Вход');
                break;

            case 5: //выход
                $event = __('Выход');
                break;

            default:
                return null;
        }

        $photo = $this->person->photos()->first();

        $photo_url = "https://{$_SERVER['HTTP_HOST']}/img/ac/s/" . ($photo->id ?? 0) . ".jpg";

        return $this->notification_body = [
            'title' => $event,
            'body' => "{$this->person->f} {$this->person->i}",
            'icon' => $photo_url,
            'click_action' => url('/')
        ];
    }

    /**
     * Отправляет уведомление
     *
     * @param int|null $user_id ID пользователя, по-умолчанию не указан,
     *                            тогда будет отправлено всем пользователям
     *
     * @return void
     * @throws \Exception
     */
    private function send(int $user_id = null): void
    {
        $registration_ids = [];

        $user = App\User::find($user_id);

        $notification = new App\Notification();
        $notification->hash = bin2hex(random_bytes(16));
        $notification->controller_id = $this->controller->id ?? null;
        $notification->user_id = $user->id;

        $this->notification_body['click_action'] = url("users/notification/$notification->hash");

        $tokens = $user->tokens;

        foreach ($tokens as $token) {
            $registration_ids[] = $token->token;
        }

        $request_body = [
            'notification' => $this->notification_body,
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
    }
}

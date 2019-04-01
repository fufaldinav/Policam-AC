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
use Log;

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
     * Подготавливает фото для отображения
     *
     * @param App\Notification $notification
     *
     * @return array
     */
    public static function getPhotos(App\Notification $notification): array
    {
        $datetime = Carbon::createFromTimeString($notification->created_at);

        $cameras = $notification->controller->cameras;

        $camera_path = config('ac.camera_path');
        $photos = [];

        foreach ($cameras as $camera) {
            $path = $camera_path . DIRECTORY_SEPARATOR . config("ac.cameras.$camera->type");
            $path .= DIRECTORY_SEPARATOR . $camera->name;
            $photos = array_merge($photos, self::findLastFiles($path, $datetime, 5));
        }

        return $photos;
    }

    /**
     * Сканирует директорию и возвращает полные пути к файлам
     *
     * @param $dir
     * @param Carbon $datetime
     * @param int $delta
     *
     * @return array
     */
    private static function findLastFiles($dir, Carbon $datetime, $delta = 5): array
    {
        $first_deadline = clone $datetime;
        $first_deadline->subSeconds($delta);
        $second_deadline = clone $datetime;
        $second_deadline->addSeconds($delta);

        $files = [];

        do {
            $path = $dir . DIRECTORY_SEPARATOR . $first_deadline->toDateString();
            $path .= DIRECTORY_SEPARATOR . '001' . DIRECTORY_SEPARATOR . 'jpg';
            $path .= DIRECTORY_SEPARATOR . $first_deadline->isoFormat('HH');
            $path .= DIRECTORY_SEPARATOR . $first_deadline->isoFormat('mm');
            if (is_dir($path)) {
                $seconds = [];
                for ($i = $first_deadline->second; $i < 60; $i++) {
                    $seconds[] = $i;
                    if ($first_deadline->diffInSeconds($second_deadline, false) <= 0) {
                        break;
                    }
                    $first_deadline->addSecond();
                }
                $content = array_diff(scandir($path), ['..', '.', 'DVRWorkDirectory']);
                foreach ($content as $item) {
                    $file = realpath($path . DIRECTORY_SEPARATOR . $item);
                    $item_second = substr($item, 0, 2);
                    if (! is_dir($file) && in_array($item_second, $seconds)) {
                        $files[] = $file;
                    }
                }
            } else {
                $first_deadline->second(0);
                $first_deadline->addMinute();
            }
        } while ($first_deadline->diffInSeconds($second_deadline, false) > 0);

        return $files;
    }

    /**
     * Сканирует директорию и возвращает полные пути к файлам
     *
     * @param $dir
     * @param array $result
     *
     * @return array
     */
    private static function scanDir($dir, &$result = []): array
    {
        if (is_dir($dir)) {
            $content = array_diff(scandir($dir), ['..', '.', 'DVRWorkDirectory']);
            foreach ($content as $item) {
                $path = realpath($dir . DIRECTORY_SEPARATOR . $item);

                if (!is_dir($path)) {
                    $result[] = $path;
                } else {
                    self::scanDir($path, $result);
                }
            }
        }

        return $result;
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
                $event = __('ac/common.entrace');
                break;

            case 5: //выход
                $event = __('ac/common.exit');
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

        $logger = new Logger();
        $logger->add('push', "USER: $user->id || $response");
        $logger->write();
    }
}

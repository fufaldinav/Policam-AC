<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 09.07.2019
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

namespace App\Policam\Ac\Policont;

use App;
use App\Events\ControllerConnected;
use App\Events\EventReceived;
use App\Events\PingReceived;
use Carbon\Carbon;

final class Request
{
    private $request;

    public function __construct($request = null)
    {
        $this->request = json_decode($request);
    }

    /**
     * Обрабатывает входящее сообщение
     *
     * @return string|null Сообщение в формате JSON или NULL,
     *                     если сообщение от неизвестного контроллера
     */
    public function handle(): ?string
    {
        if (! is_object($this->request)) {
            return null;
        }

        $datetime = Carbon::now()->toDateTimeString();

        $response = new Response();

        $sn = $this->request->sn ?? null;

        if (is_null($sn)) {
            return null;
        }

        $ctrl = App\Controller::where(['sn' => $sn])->first();

        if (! $ctrl) {
            return null;
        }

        $messages = $this->request->messages;

        $ctrl->last_conn = $datetime;
        $ctrl->save();

        event(new ControllerConnected($ctrl));

        foreach ($messages as $message) {
            /*
             | Ответ на задание
             */
            if (! isset($message->operation) && isset($message->success)) {
                if ($message->success === 1) {
                    App\Task::where(['task_id' => $message->id])->delete();
                }
            } /*
             | Запуск контроллера
             */
            elseif ($message->operation === 'power_on') {
                $out_message = new OutgoingMessage();
                $out_message->setOperation('set_active');
                $out_message->setActive($ctrl->active);
                $response->addMessage($out_message);

                $ctrl->fw = $message->fw;
                $ctrl->ip = $message->controller_ip;

                if (isset($message->devices)) {
                    $ctrl->devices = $message->devices;
                }

                $ctrl->save();
            } /*
             | Проверка доступа
             */
            elseif ($message->operation === 'check_access') {
                $out_message = new OutgoingMessage($message->id);
                $out_message->setOperation('check_access');
                $out_message->setGranted(0);

                $card = App\Card::firstOrNew(['wiegand' => $message->card]);

                if ($card->person_id > 0) {
                    $out_message->setGranted(1);
                }

                $card->last_conn = $datetime;
                $card->controller_id = $ctrl->id;

                $card->save();

                $response->addMessage($out_message);
            } /*
             | Пинг от контроллера
             */
            elseif ($message->operation === 'ping') {
                $devices = [];

                if (isset($message->devices)) {
                    foreach ($message->devices as $device) {
                        $devices[$device->id] = [
                            'timeout' => $device->timeout,
                            'alarm' => $device->alarm ?? null,
                            'sd_error' => $device->sd_error ?? null,
                        ];
                    }
                } else {
                    if (isset($message->timeouts) && isset($message->alarm) && isset($message->sd_errors)) {
                        for ($i = 0; $i < strlen($message->timeouts); $i++) {
                            $devices[$i] = [
                                'timeout' => $message->timeouts[$i],
                                'alarm' => $message->alarm[$i],
                                'sd_errors' => $message->sd_errors[$i],
                            ];
                        }
                    }
                }

                $ctrl->devices_status = json_encode($devices);
                $ctrl->save();

                event(new PingReceived($ctrl->id, $devices));
            } /*
             | Cобытия на контроллере
             */
            elseif ($message->operation === 'events') {
                $out_message = new OutgoingMessage($message->id);
                $out_message->setOperation('events');

                //чтение событий
                foreach ($message->events as $inc_event) {
                    if ($inc_event->event == 21) {
                        if ($ctrl->devices_voltage == null) {
                            $devices_voltage = [];
                        } else {
                            $devices_voltage = json_decode($ctrl->devices_voltage);
                        }

                        $devices_voltage[hexdec($inc_event->flag)] = hexdec($inc_event->card);
                        $ctrl->devices_voltage = json_encode($devices_voltage);
                        $ctrl->save();

                        $out_message->eventCounter();
                    } else {
                        $wiegand = str_pad($inc_event->card, 12, '0', STR_PAD_LEFT);
                        $dateTimeString = Carbon::createFromTimestamp($inc_event->timestamp, date_default_timezone_get())->toDateTimeString();

                        $card = App\Card::firstOrNew(['wiegand' => $wiegand]);
                        $card->last_conn = $dateTimeString;
                        $card->controller_id = $ctrl->id;
                        $card->save();

                        $event = new App\Event;
                        $event->controller_id = $ctrl->id;
                        $event->event = $inc_event->event;
                        $event->flag = $inc_event->flag;
                        $event->time = $dateTimeString;
                        $event->card_id = $card->id;
                        $event->save();

                        $out_message->eventCounter();

                        event(new EventReceived($event));
                    }
                }

                $response->addMessage($out_message);
            }
        }

        $task = $ctrl->tasks()->first();

        if ($task) {
            $response->addMessage($task->json);
        }

        return $response->getMessages();
    }
}

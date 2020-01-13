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
use App\Events\DeviceChangedStatus;
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
     * @throws \Exception
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

        if (isset($this->request->eq)) {
            $ctrl->events_queue = $this->request->eq;
        }
        if (isset($this->request->mq)) {
            $ctrl->messages_queue = $this->request->mq;
        }
        if (isset($this->request->ebl)) {
            $ctrl->events_bl = $this->request->ebl;
        }
        if (isset($this->request->mbl)) {
            $ctrl->messages_bl = $this->request->mbl;
        }
        if (isset($this->request->alarm)) {
            $ctrl->alarm = $this->request->alarm;
        }
        if (isset($this->request->sd_error)) {
            $ctrl->sd_error = $this->request->sd_error;
        }

        $ctrl->save();

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
                    $devicesFromCtrl = $message->devices;
                    $devicesInDbCount = $ctrl->devices()->count();

                    for ($i = $devicesFromCtrl; $i < $devicesInDbCount; $i++) {
                        $device = App\Device::where('controller_id', $ctrl->id)->where('address', $i)->first();
                        $device->delete();
                    }

                    foreach ($ctrl->devices as $device) {
                        $device->name = $ctrl->name . ' Slave #' . $device->address;
                        $device->type = $ctrl->type;
                        $device->fw = $ctrl->fw;
                        $device->save();
                    }

                    for ($i = $devicesInDbCount; $i < $devicesFromCtrl; $i++) {
                        $ctrl->devices()->create([
                            'address' => $i,
                            'name' => $ctrl->name . ' Slave #' . $i,
                            'type' => $ctrl->type,
                            'fw' => $ctrl->fw,
                        ]);
                    }
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
                $deviceChangedStatus = false;

                if (isset($message->devices)) {
                    $devices = $message->devices;
                    foreach ($ctrl->devices as $device) {
                        if ($device->timeout == 0 && $devices[$device->address]->timeout >= 3) {
                            $device->timeout = 1;
                            $deviceChangedStatus = true;
                        } else if ($device->timeout == 1 && $devices[$device->address]->timeout < 3) {
                            $device->timeout = 0;
                            $deviceChangedStatus = true;
                        }
                        $device->alarm = $devices[$device->address]->alarm ?? 0;
                        $device->sd_error = $devices[$device->address]->sd_error ?? 0;
                        $device->save();
                    }
                } else {
                    if (isset($message->timeouts) && isset($message->alarms) && isset($message->sd_errors)) {
                        foreach ($ctrl->devices as $device) {
                            if (($device->timeout == 0 && $message->timeouts[$device->address] > 0) || ($device->timeout == 1 && $message->timeouts[$device->address] == 0)) {
                                $deviceChangedStatus = true;
                            }
                            $device->timeout = $message->timeouts[$device->address];
                            $device->alarm = $message->alarms[$device->address];
                            $device->sd_error = $message->sd_errors[$device->address];
                            $device->save();
                        }
                    }
                }

                if ($deviceChangedStatus) {
                    event(new DeviceChangedStatus($ctrl));
                }
            } /*
             | Cобытия на контроллере
             */
            elseif ($message->operation === 'events') {
                $out_message = new OutgoingMessage($message->id);
                $out_message->setOperation('events');

                //чтение событий
                foreach ($message->events as $inc_event) {
                    if ($inc_event->event == 21) {
                        $deviceAddress = hexdec($inc_event->flag);
                        $device = App\Device::where('controller_id', $ctrl->id)->where('address', $deviceAddress)->first();
                        $device->voltage = hexdec($inc_event->card) / 100;
                        $device->save();

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
                    }
                }

                $response->addMessage($out_message);
            } elseif ($message->operation === 'event') {
                $out_message = new OutgoingMessage($message->id);
                $out_message->setOperation('event');

                $wiegand = str_pad($message->card, 12, '0', STR_PAD_LEFT);
                $dateTimeString = Carbon::createFromTimestamp($message->timestamp, date_default_timezone_get())->toDateTimeString();

                $card = App\Card::firstOrNew(['wiegand' => $wiegand]);
                $card->last_conn = $dateTimeString;
                $card->controller_id = $ctrl->id;
                $card->save();

                $device = App\Device::firstOrNew([
                    'controller_id' => $ctrl->id,
                    'address' => $message->device,
                ]);

                if (!$device->exists) {
                    $device->name = $ctrl->name . ' Slave #' . $device->address;
                    $device->type = $ctrl->type;
                    $device->fw = $ctrl->fw;
                }
                $device->voltage = $message->voltage;
                $device->events_queue = $message->eq;
                $device->events_bl = $message->ebl;
                $device->save();

                $device->events()->create([
                    'controller_id' => $ctrl->id,
                    'device_id' =>$device->id,
                    'event' => $message->event,
                    'flag' => $message->flag,
                    'time' => $dateTimeString,
                    'ms' => $message->ms,
                    'voltage' => $message->voltage,
                    'card_id' => $card->id,
                ]);

                $out_message->eventSuccess($message->id);

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

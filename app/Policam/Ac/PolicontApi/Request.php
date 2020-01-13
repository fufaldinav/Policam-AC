<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 26.12.2019
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

namespace App\Policam\Ac\PolicontApi;

use App;
use App\Events\DeviceChangedStatus;
use App\Events\PolicamMasterPowerOn;
use App\Events\PolicamSlavePowerOn;
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
        if (!is_object($this->request)) {
            return null;
        }

        $datetime = Carbon::now()->toDateTimeString();

        $type = $this->request->type ?? null;
        if ($type !== 'Policont') {
            return null;
        }

        $ctrl = App\Controller::firstOrCreate([
            'sn' => $this->request->sn,
            'type' => $this->request->type,
        ]);
        $ctrl->last_conn = $datetime;
        $ctrl->events_queue = $this->request->eq;
        $ctrl->messages_queue = $this->request->mq;
        $ctrl->events_bl = $this->request->ebl;
        $ctrl->messages_bl = $this->request->mbl;
        $ctrl->alarm = $this->request->alarm;
        $ctrl->sd_error = $this->request->sd_error;
        $ctrl->save();

        $response = new Response();
        $messages = $this->request->messages;

        if ($ctrl->active === 0) {
            $out_message = new OutgoingMessage();
            $out_message->setOperation('activation');
            $out_message->setDeactivated();
            $out_message->setOffline();

            $response->addMessage($out_message);
        } else {
            foreach ($messages as $message) {
                if ($message->operation === 'activation') {
                    $out_message = new OutgoingMessage();
                    $out_message->setOperation('activation');
                    $out_message->setActivated();
                    $out_message->setOnline();

                    $response->addMessage($out_message);

                    $ctrl->fw = $message->fw;
                    $ctrl->ip = $message->controller_ip;
                    $ctrl->save();

                    $devicesFromCtrl = $message->devices;
                    $devicesInDbCount = $ctrl->devices()->count();

                    for ($i = $devicesFromCtrl; $i < $devicesInDbCount; $i++) {
                        App\Device::where([
                            'controller_id' => $ctrl->id,
                            'address' => $i,
                        ])->delete();
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

                    event(new PolicamMasterPowerOn($ctrl));
                } else if ($message->operation === 'success') {
                    if ($message->success === 1) {
                        App\Task::where([
                            'task_id' => $message->id,
                            'controller_id' => $ctrl->id,
                        ])->delete();
                    }
                } else if ($message->operation === 'connection') {
                    $out_message = new OutgoingMessage();
                    $out_message->setOperation('connection');
                    $out_message->setOnline();

                    $response->addMessage($out_message);

                    $ctrl->ip = $message->controller_ip;
                    $ctrl->save();
                } else if ($message->operation === 'ping') {
                    $deviceChangedStatus = false;

                    foreach ($ctrl->devices as $device) {
                        if (($device->timeout == 0 && $message->timeouts[$device->address] > 0) || ($device->timeout == 1 && $message->timeouts[$device->address] == 0)) {
                            $deviceChangedStatus = true;
                        }
                        $device->timeout = $message->timeouts[$device->address];
                        $device->alarm = $message->alarms[$device->address];
                        $device->sd_error = $message->sd_errors[$device->address];
                        $device->save();
                    }

                    if ($deviceChangedStatus) {
                        event(new DeviceChangedStatus($ctrl));
                    }
                } else if ($message->operation === 'event') {
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

                    $event = $device->events()->create([
                        'controller_id' => $ctrl->id,
                        'device_id' =>$device->id,
                        'event' => $message->event,
                        'flag' => $message->flag,
                        'time' => $dateTimeString,
                        'ms' => $message->ms,
                        'voltage' => $message->voltage,
                        'card_id' => $card->id,
                    ]);

                    if ($event->event === 21) {
                        event(new PolicamSlavePowerOn($device));
                    }

                    $out_message->eventSuccess($message->id);

                    $response->addMessage($out_message);
                }
            }
        }

        $task = $ctrl->tasks()->first();

        if ($task) {
            $response->addMessage($task->json);
        }

        return $response->getMessages();
    }
}

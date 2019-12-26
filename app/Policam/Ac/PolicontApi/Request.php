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

        $response = new Response();

        $sn = $this->request->sn ?? null;

        if (is_null($sn)) {
            return null;
        }

        $ctrl = App\Controller::where(['sn' => $sn])->first();

        if (!$ctrl) {
            return null; //TODO сообщение для контроллера, что его нет в списке
        }

        $messages = $this->request->messages;

        $ctrl->last_conn = $datetime;
        $ctrl->events_queue = $this->request->eq;
        $ctrl->messages_queue = $this->request->mq;
        $ctrl->events_bl = $this->request->ebl;
        $ctrl->messages_bl = $this->request->mbl;
        $ctrl->alarm = $this->request->alarm;
        $ctrl->sd_error = $this->request->sd_error;

        $ctrl->save();

        foreach ($messages as $message) {
            //Ответ на задание
            if (!isset($message->operation) && isset($message->success)) {
                if ($message->success === 1) {
                    App\Task::where(['task_id' => $message->id])->delete();
                }
            } elseif ($message->operation === 'power_on') {
                $out_message = new OutgoingMessage();
                $out_message->setOperation('set_active');
                $out_message->setActive($ctrl->active);
                $response->addMessage($out_message);

                $ctrl->fw = $message->fw;
                $ctrl->ip = $message->controller_ip;

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

                $ctrl->save();
                event(new PolicamMasterPowerOn($ctrl));
            } elseif ($message->operation === 'connect') {
                $out_message = new OutgoingMessage();
                $out_message->setOperation('set_online');
                $out_message->setOnline();
                $response->addMessage($out_message);

                $ctrl->ip = $message->controller_ip;

                $ctrl->save();
            } elseif ($message->operation === 'ping') {
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
            } elseif ($message->operation === 'event') {
                $out_message = new OutgoingMessage($message->id);
                $out_message->setOperation('event');

                $wiegand = str_pad($message->card, 12, '0', STR_PAD_LEFT);
                $dateTimeString = Carbon::createFromTimestamp($message->timestamp, date_default_timezone_get())->toDateTimeString();

                $card = App\Card::firstOrNew(['wiegand' => $wiegand]);
                $card->last_conn = $dateTimeString;
                $card->controller_id = $ctrl->id;
                $card->save();

                $event = new App\Event;
                $event->controller_id = $ctrl->id;
                $event->device_id = $message->device;
                $event->event = $message->event;
                $event->flag = $message->flag;
                $event->time = $dateTimeString;
                $event->ms = $message->ms;
                $event->voltage = $message->voltage;
                $event->card_id = $card->id;
                $event->save();

                $device = App\Device::where([
                    'controller_id' => $ctrl->id,
                    'address' => $message->device,
                ])->first();

                if (isset($device)) {
                    $device->voltage = $message->voltage;
                    $device->events_queue = $message->eq;
                    $device->events_bl = $message->ebl;
                    $device->save();
                    $device->events()->save($event);
                }

                $out_message->eventSuccess($message->id);

                if ($event->event === 21) {
                    event(new PolicamSlavePowerOn($device));
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

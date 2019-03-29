<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 28.03.2019
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

namespace App\Policam\Ac\Z5RWEB;

use App;
use App\Policam\Ac\Logger;
use App\Policam\Ac\Notificator;
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
        if (is_null($this->request)) {
            return null;
        }

        $datetime = Carbon::now()->toDateTimeString();

        $response = new Response($datetime);

        $logger = new Logger;

        $type = $this->request->type;
        $sn = $this->request->sn;
        $messages = $this->request->messages;

        $ctrl = App\Controller::where(['sn' => $sn])->first();

        if (! $ctrl) {
            $logger->add('inc', "TYPE: $type || SN: $sn || Неизвестный контроллер");
            $logger->write();

            return null;
        }

        $ctrl->last_conn = $datetime;
        $ctrl->save();

        $logger->add('inc', "TYPE: $type || SN: $sn || " . json_encode($messages));

        foreach ($messages as $message) {
            /*
             | Ответ на задание
             */
            if (! isset($message->operation) && isset($message->success)) {
                if ($message->success === 1) {
                    App\Task::where(['task_id' => $message->id])->delete();
                }
            }
            /*
             | Запуск контроллера
             */
            elseif ($message->operation === 'power_on') {
                $out_message = new OutgoingMessage();
                $out_message->setOperation('set_active');
                $out_message->setActive($ctrl->active);
                $out_message->setOnline($ctrl->online);
                $response->addMessage($out_message);

                $ctrl->fw = $message->fw;
                $ctrl->conn_fw = $message->conn_fw;
                $ctrl->ip = $message->controller_ip;

                $ctrl->save();
            }
            /*
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
            }
            /*
             | Пинг от контроллера
             */
            elseif ($message->operation === 'ping') {
                //do nothing
            }
            /*
             | Cобытия на контроллере
             */
            elseif ($message->operation === 'events') {
                $out_message = new OutgoingMessage($message->id);
                $out_message->setOperation('events');

                //чтение событий
                foreach ($message->events as $inc_event) {
                    $card = App\Card::firstOrNew(['wiegand' => $inc_event->card]);
                    $card->last_conn = $datetime;
                    $card->controller_id = $ctrl->id;
                    $card->save();

                    $event = new App\Event;
                    $event->controller_id = $ctrl->id;
                    $event->event = $inc_event->event;
                    $event->flag = $inc_event->flag;
                    $event->time = $inc_event->time;
                    $event->card_id = $card->id;
                    $event->save();

                    $out_message->eventCounter();

                    $person = $card->person;

                    $subscribers = $person->users;

                    $notificator = new Notificator();
                    $notification = $notificator->generate($person->id, $event->event); // TODO уведомления

                    foreach ($subscribers as $sub) {
                        if (isset($notification)) {
                            $notificator->send($notification, $sub->id);
                        }
                    }
                }

                $response->addMessage($out_message);
            }
        }

        $task = $ctrl->tasks()->first();

        if ($task) {
            $response->addMessage($task->json);
        }

        $logger->add('out', "TYPE: $type || SN: $sn || {$response->getMessages()}");
        $logger->write();

        return $response->getMessages();
    }
}

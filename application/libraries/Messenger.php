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
 * Class Messenger
 *
 * @property Logger $logger
 * @property Notificator $notificator
 */
class Messenger extends Ac
{
    /**
     * Таймаут одного long poll
     *
     * @var int
     */
    private $timeout;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->timeout = $this->CI->config->item('long_poll_timeout', 'ac');
    }

    /**
     * Обрабатывает входящее сообщение
     *
     * @param string|null $inc_json_msg Входящее JSON сообщение
     *
     * @return string|null Сообщение в формате JSON или NULL,
     *                     если сообщение от неизвестного контроллера
     */
    public function handle(string $inc_json_msg = null): ?string
    {
        if (is_null($inc_json_msg)) {
            return null;
        }

        $this->CI->load->helper('date');

        $out_msg = new stdClass;

        $time = now();

        $out_msg->date = mdate('%Y-%m-%d %H:%i:%s', $time);
        $out_msg->interval = 10;
        $out_msg->messages = [];

        $decoded_msg = json_decode($inc_json_msg);

        if (is_null($decoded_msg)) {
            return null;
        }

        $this->load('Controllers');

        $this->CI->load->library('logger');
        $logger = $this->CI->logger;

        $type = $decoded_msg->type;
        $sn = $decoded_msg->sn;
        $inc_msgs = $decoded_msg->messages;

        $ctrl = new \ORM\Controllers(['sn' => $sn]);

        if (! isset($ctrl->id)) {
            $logger->add('inc', "TYPE: $type || SN: $sn || $inc_json_msg");
            $logger->write();

            return null;
        }

        $this->load('Tasks');

        $ctrl->last_conn = $time;
        $ctrl->save();

        $logger->add('inc', "TYPE: $type || SN: $sn || $inc_json_msg");

        foreach ($inc_msgs as $inc_m) {
            /*
             | Ответ на задание
             */
            if (! isset($inc_m->operation) && isset($inc_m->success)) {
                if ($inc_m->success === 1) {
                    $task = new \ORM\Tasks(['task_id' => $inc_m->id]);
                    $task->remove();
                }
            }
            /*
             | Запуск контроллера
             */
            elseif ($inc_m->operation === 'power_on') {
                $out_m = new stdClass;
                $out_m->id = 0;
                $out_m->operation = 'set_active';
                $out_m->active = $ctrl->active;
                $out_m->online = $ctrl->online;
                $out_msg->messages[] = $out_m;

                $ctrl->fw = $inc_m->fw;
                $ctrl->conn_fw = $inc_m->conn_fw;
                $ctrl->ip = $inc_m->controller_ip;

                $ctrl->save();
            }
            /*
             | Проверка доступа
             */
            elseif ($inc_m->operation === 'check_access') {
                $out_m = new stdClass;
                $out_m->id = $inc_m->id; //запись верна, т.к. ответ должен быть с тем же id
                $out_m->operation = 'check_access';
                $out_m->granted = 0;

                $this->load('Cards');

                $card = new \ORM\Cards(['wiegand' => $inc_m->card]);

                if (isset($card->person_id) && $card->person_id > 0) {
                    $out_m->granted = 1;
                }

                if (! isset($card->wiegand)) {
                    $card->wiegand = $inc_m->card;
                }

                $card->last_conn = $time;
                $card->controller_id = $ctrl->id;

                $card->save();

                $out_msg->messages[] = $out_m;
            }
            /*
             | Пинг от контроллера
             */
            elseif ($inc_m->operation === 'ping') {
                //do nothing
            }
            /*
             | Cобытия на контроллере
             */
            elseif ($inc_m->operation === 'events') {
                $this->load(['Cards', 'Events', 'Persons', 'Users']);

                $this->CI->load->library('notificator');

                $events_count = 0;

                //чтение событий
                foreach ($inc_m->events as $inc_event) {
                    $card = new \ORM\Cards(['wiegand' => $inc_event->card]);

                    if (! isset($card->wiegand)) {
                          $card->wiegand = $inc_event->card;
                    }

                    $card->last_conn = $time;
                    $card->controller_id = $ctrl->id;

                    $card->save();

                    $event = new \ORM\Events;

                    $event->controller_id = $ctrl->id;
                    $event->event = $inc_event->event;
                    $event->flag = $inc_event->flag;
                    $event->time = human_to_unix($inc_event->time);
                    $event->server_time = $time;
                    $event->card_id = $card->id;

                    $events_count += $event->save();

                    $person = $card->person;

                    $subscribers = $person->users->get();

                    $notification = $this->CI->notificator->generate($person->id, $event->event);

                    foreach ($subscribers as $sub) {
                        if (isset($notification)) {
                            $this->CI->notificator->send($notification, $sub->id);
                        }
                    }
                }

                $out_m = new stdClass;
                $out_m->id = $inc_m->id; //запись верна, т.к. ответ должен быть с тем же id
                $out_m->operation = 'events';
                $out_m->events_success = $events_count;
                $out_msg->messages[] = $out_m;
            }
        }

        $tasks = $ctrl->tasks->get();
        $task = $ctrl->tasks->first();

        if (isset($task)) {
            $out_msg->messages[] = json_decode($task->json);
        }

        $out_json_msg = json_encode($out_msg);

        $logger->add('out', "TYPE: $type || SN: $sn || $out_json_msg");
        $logger->write();

        return $out_json_msg;
    }

    /**
     * Реализует long polling
     *
     * @param int[]    $event_types Типы событий
     * @param int|null $time        Время последнего запроса
     *
     * @return mixed[] События от контроллера
     */
    public function polling(array $event_types, int $time = null): array
    {
        $time = $time ?? now();

        $this->load(['Controllers', 'Organizations', 'Users']);

        $user = new \ORM\Users($this->CI->ion_auth->user()->row()->id);

        $orgs = $user->organizations->get(); //TODO

        $ctrl_list = [];

        foreach ($orgs as $org) {
            $ctrl_list = array_merge($ctrl_list, $org->controllers->get());
        }

        if ($ctrl_list) {
            session_write_close();

            $this->load('Events');

            while ($this->timeout > 0) {
                $controllers = [];

                foreach ($ctrl_list as $ctrl) {
                    $controllers[] = $ctrl->id;
                }

                $events = new \ORM\Lists('events');

                $events = $events
                    ->whereIn('event', $event_types)
                    ->whereIn('controller_id', $controllers)
                    ->where('server_time >', $time)
                    ->orderBy('time DESC')
                    ->get();

                if ($events) {
                    return $events;
                }

                $this->timeout--;
                sleep(1);
            }
        } else {
            return [];
        }

        return [];
    }
}

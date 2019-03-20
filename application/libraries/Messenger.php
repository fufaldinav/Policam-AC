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
 * @property Notificator $notificator
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Event_model $event
 * @property Person_model $person
 * @property Task_model $task
 */
class Messenger extends Ac
{
    /**
     * Каталог с логами
     *
     * @var string
     */
    private $_log_path;

    /**
     * Таймаут одного long poll
     *
     * @var int
     */
    private $_timeout;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->_log_path = self::$_CI->config->item('log_path', 'ac');

        if (! is_dir($this->_log_path)) {
            mkdir($this->_log_path, 0755, true);
        }

        $this->_timeout = self::$_CI->config->item('long_poll_timeout', 'ac');
    }

    /**
     * Обрабатывает входящее сообщение
     *
     * @param string $inc_json_msg Входящее JSON сообщение
     *
     * @return string|null Сообщение в формате JSON или NULL,
     *                     если сообщение от неизвестного контроллера
     */
    public function handle(string $inc_json_msg): ?string
    {
        self::$_CI->load->helper(['date', 'file']);

        $out_msg = new stdClass;

        $time = now('Asia/Yekaterinburg');

        $log_date = mdate('%Y-%m-%d', $time);

        $out_msg->date = mdate('%Y-%m-%d %H:%i:%s', $time);
        $out_msg->interval = 10;
        $out_msg->messages = [];

        $decoded_msg = json_decode($inc_json_msg);

        if (! isset($decoded_msg)) {
            exit;
        }

        $type = $decoded_msg->type;
        $sn = $decoded_msg->sn;
        $inc_msgs = $decoded_msg->messages;

        $path = "$this->log_path/inc-$log_date.txt";

        $this->model('ctrl');

        if ($this->ctrl->get_by('sn', $sn)) {
            $this->ctrl->last_conn = $time;

            $this->ctrl->save();

            write_file($path, "TYPE: $type || SN: $sn || $inc_json_msg\n", 'a');
        } else {
            write_file($path, "TYPE: $type || SN: $sn || Неизвестный контроллер\n", 'a');

            return null;
        }

        $this->model('task');

        //чтение json сообщения
        foreach ($inc_msgs as $inc_m) {
            //
            //простой ответ
            //
            if (! isset($inc_m->operation) && isset($inc_m->success)) {
                if ($inc_m->success === 1) {
                    $this->task->delete($inc_m->id);
                }
            }
            //
            //запуск контроллера
            //
            elseif ($inc_m->operation === 'power_on') {
                $out_m = new stdClass();
                $out_m->id = 0;
                $out_m->operation = 'set_active';
                $out_m->active = $this->ctrl->active;
                $out_m->online = $this->ctrl->online;
                $out_msg->messages[] = $out_m;

                $this->ctrl->fw = $inc_m->fw;
                $this->ctrl->conn_fw = $inc_m->conn_fw;
                $this->ctrl->ip = $inc_m->controller_ip;

                $this->ctrl->save();
            }
            //
            //проверка доступа
            //
            elseif ($inc_m->operation === 'check_access') {
                $out_m = new stdClass();
                $out_m->id = $inc_m->id; //запись верна, т.к. ответ должен быть с тем же id
                $out_m->operation = 'check_access';
                $out_m->granted = 0;

                $this->model('card');

                $this->card->get_by('wiegand', $inc_m->card);

                if (isset($this->card->person_id) && $this->card->person_id > 0) {
                    $out_m->granted = 1;
                }

                $this->card->wiegand = $inc_m->card;
                $this->card->last_conn = $time;
                $this->card->controller_id = $this->ctrl->id;

                $this->card->save();

                $out_msg->messages[] = $out_m;
            }
            //
            //пинг от контроллера
            //
            elseif ($inc_m->operation === 'ping') {
                //do nothing
            }
            //
            //события на контроллере
            //
            elseif ($inc_m->operation === 'events') {
                $this->model('event');

                //чтение событий
                foreach ($inc_m->events as $event) {
                    $this->model('card');

                    $this->card->get_by('wiegand', $event->card);

                    $this->card->wiegand = $event->card;
                    $this->card->last_conn = $time;
                    $this->card->controller_id = $this->ctrl->id;

                    $this->card->save();

                    $this->event->controller_id = $this->ctrl->id;
                    $this->event->event = $event->event;
                    $this->event->flag = $event->flag;
                    $this->event->time = human_to_unix($event->time);
                    $this->event->server_time = $time;
                    $this->event->card_id = $this->card->id;

                    $this->event->add_to_list();

                    $this->model('person');

                    $subscribers = $this->person->get_users($this->card->person_id);

                    self::$_CI->load->library('notificator');

                    foreach ($subscribers as $sub) {
                        $notification = self::$_CI->notificator->generate($this->card->person_id, $event->event);

                        if (count($notification) > 0) {
                            $response = self::$_CI->notificator->send($notification, $sub->user_id);

                            $path = "$this->log_path/push-$log_date.txt";
                            write_file($path, "USER: $sub->user_id || PERSON: {$this->card->person_id} || $response\n", 'a');
                        }
                    }
                }

                $this->event->save_list();

                $out_m = new stdClass();
                $out_m->id = $inc_m->id;
                $out_m->operation = 'events';
                $out_m->events_success = count($this->event->get_list());
                $out_msg->messages[] = $out_m;
            }
        }

        if ($this->task->get_last($this->ctrl->id)) {
            $out_msg->messages[] = json_decode($this->task->json);
        }

        $out_json_msg = json_encode($out_msg);

        $path = "$this->log_path/out-$log_date.txt";
        write_file($path, "TYPE: $type || SN: $sn || $out_json_msg\n", 'a');

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
        $time = $time ?? now('Asia/Yekaterinburg');

        $user_id = self::$_CI->ion_auth->user()->row()->id; //TODO

        $this->model('org');
        $this->model('ctrl');

        $org_list = $this->org->get_list($user_id); //TODO

        $ctrl_list = [];

        foreach ($org_list as $org) {
            $ctrl_list = array_merge($ctrl_list, $this->ctrl->get_list($org->id));
        }

        if ($ctrl_list) {
            session_write_close();

            $this->model('event');

            while ($this->_timeout > 0) {
                $controllers = [];

                foreach ($ctrl_list as $ctrl) {
                    $controllers[] = $ctrl->id;
                }

                $events = $this->event->list_get_last($time, $event_types, $controllers);

                if ($events) {
                    return $events;
                }

                $this->_timeout--;
                sleep(1);
            }
        } else {
            return [];
        }

        return [];
    }
}

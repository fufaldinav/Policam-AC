<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
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
* Class Server Model
* @property Card_model $card
* @property Ctrl_model $ctrl
* @property Notification_model $notification
* @property Task_model $task
*/
class Server_model extends CI_Model
{
	/**
	 * Каталог с логами
	 *
	 * @var string $log_path
	 */
	private $log_path;

	public function __construct()
	{
		parent::__construct();

		$this->config->load('ac', true);

		$this->load->database();

		$this->load->model('ac/card_model', 'card');
		$this->load->model('ac/ctrl_model', 'ctrl');
		$this->load->model('ac/notification_model', 'notification');
		$this->load->model('ac/task_model', 'task');

		$this->log_path = $this->config->item('log_path', 'ac');

		if (!is_dir($this->log_path)) {
			mkdir($this->log_path, 0755, true);
		}
	}

	/**
	 * Обрабатывает входящее сообщение
	 *
	 * @param string $inc_json_msg Входящее JSON сообщение
	 *
	 * @return string|null Сообщение в формате JSON или NULL, если сообщение от неизвестного контроллера
	 */
	public function handle_msg(string $inc_json_msg): ?string
	{
		$this->load->helper(['date', 'file']);

		$out_msg = new stdClass();

		$time = now('Asia/Yekaterinburg');

		$log_date = mdate('%Y-%m-%d', $time);

		$out_msg->date = mdate('%Y-%m-%d %H:%i:%s', $time);
		$out_msg->interval = 10;
		$out_msg->messages = [];

		$decoded_msg = json_decode($inc_json_msg);

		if (!isset($decoded_msg)) {
			exit;
		}

		$type = $decoded_msg->type;
		$sn = $decoded_msg->sn;
		$inc_msgs = $decoded_msg->messages;

		$ctrl = $this->ctrl->get_by_sn($sn);

		$path = "$this->log_path/inc-$log_date.txt";

		if (isset($ctrl)) {
			$ctrl->last_conn = $time;

			$this->ctrl->update($ctrl);

			write_file($path, "TYPE: $type || SN: $sn || $inc_json_msg\n", 'a');
		} else {
			write_file($path, "TYPE: $type || SN: $sn || Неизвестный контроллер\n", 'a');

			return null;
		}

		//чтение json сообщения
		foreach ($inc_msgs as $inc_m) {
			//
			//простой ответ
			//
			if (!isset($inc_m->operation) && isset($inc_m->success)) {
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
				$out_m->active = $ctrl->active;
				$out_m->online = $ctrl->online;
				$out_msg->messages[] = $out_m;

				$ctrl->fw = $inc_m->fw;
				$ctrl->conn_fw = $inc_m->conn_fw;
				$ctrl->ip = $inc_m->controller_ip;

				$this->ctrl->update($ctrl);
			}
			//
			//проверка доступа
			//
			elseif ($inc_m->operation === 'check_access') {
				$out_m = new stdClass();
				$out_m->id = $inc_m->id; //запись верна, т.к. ответ должен быть с тем же id
				$out_m->operation = 'check_access';
				$out_m->granted = 0;

				$card = $this->card->get_by_code($inc_m->card);

				if (isset($card)) {
					if ($card->person_id != -1) {
						$out_m->granted = 1;
					}

					$this->card->set_last_conn($card->id, $ctrl->id);
				} else {
					$card->wiegand = $inc_m->card;
					$card->last_conn = $time;
					$card->controller_id = $ctrl->id;
					$card->person_id = -1;

					$this->card->add($card);
				}

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
				$events_count = 0;
				$events = [];

				//чтение событий
				foreach ($inc_m->events as $event) {
					$card = $this->card->get_by_code($event->card);
					//проверяем наличие карты в БД
					if (isset($card)) {
						$this->card->set_last_conn($card->id, $ctrl->id);
					} else {
						$card = new stdClass();

						$card->wiegand = $event->card;
						$card->last_conn = $time;
						$card->controller_id = $ctrl->id;
						$card->person_id = -1;

						$card->id = $this->card->add($card);
					}

					$events[] = [
						'controller_id' => $ctrl->id,
						'event' => $event->event,
						'flag' => $event->flag,
						'time' => human_to_unix($event->time),
						'server_time' => $time,
						'card_id' => $card->id
					];

					$subscriptions = $this->notification->check_subscription($card->person_id);

					foreach ($subscriptions as $sub) {
						$notification = $this->notification->generate($card->person_id, $event->event);

						if (count($notification) > 0) {
							$response = $this->notification->send($notification, $sub->user_id);

							$path = "$this->log_path/push-$log_date.txt";
							write_file($path, "USER: $sub->user_id || PERSON: $card->person_id || $response\n", 'a');
						}
					}

					$events_count++;
				}

				$this->save_events($events);

				$out_m = new stdClass();
				$out_m->id = $inc_m->id;
				$out_m->operation = 'events';
				$out_m->events_success = $events_count;
				$out_msg->messages[] = $out_m;
			}
		}

		$task = $this->task->get_last($ctrl->id);

		if (isset($task)) {
			$out_msg->messages[] = json_decode($task->json);
		}

		$out_json_msg = json_encode($out_msg);

		$path = "$this->log_path/out-$log_date.txt";
		write_file($path, "TYPE: $type || SN: $sn || $out_json_msg\n", 'a');

		return $out_json_msg;
	}

	/**
	 * Сохраняет события
	 *
	 * @param mixed[] $events События для сохранения в БД
	 *
	 * @return int Количество успешных записей
	 */
	public function save_events(array $events): int
	{
		$this->db->insert_batch('events', $events);

		return $this->db->affected_rows();
	}
}

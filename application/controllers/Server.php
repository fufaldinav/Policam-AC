<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Server extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ac_model');
	}

	public function index()
	{
		$LOG_PATH = '/var/www/logs';

		if (!is_dir($LOG_PATH)) {
			mkdir($LOG_PATH, 0644, true);
		}

		$this->load->helper('file');
		$this->load->helper('date');

		$inc_json_msg = file_get_contents('php://input');
		$out_msg = [];

		$time = now('Asia/Yekaterinburg');
		$out_msg['date'] = mdate('%Y-%m-%d %H:%i:%s', $time);
		$log_date = mdate('%Y-%m-%d', $time);
		$out_msg['interval'] = 10;
		$out_msg['messages'] = [];

		$decoded_msg = json_decode($inc_json_msg, true);
		$type = $decoded_msg['type'];
		$sn = $decoded_msg['sn'];
		$inc_messages = $decoded_msg['messages'];

		//запрос контроллера по серийнику из БД
		$this->db->where('sn', $sn);
		$query = $this->db->get('controllers');

		//поиск контроллера в базе
		if ($query->num_rows() > 0) {
			$active = $query->row()->active;
			$online = $query->row()->online;
			$controller_id = $query->row()->id;

			$data = ['last_conn' => $time];
			$this->db->where('id', $controller_id);
			$this->db->update('controllers', $data);
		} else {
			$path = "$LOG_PATH/inc-$log_date.txt";

			write_file($path, "TYPE: $type || SN: $sn || Неизвестный контроллер\n", 'a');

			return null;
		}

		header('Content-Type: application/json');

		//чтение json сообщения
		foreach ($inc_messages as $inc_m) {
			//
			//простой ответ
			//
			if (!isset($inc_m['operation']) && isset($inc_m['success'])) {
				if ($inc_m['success'] == 1) {
					$this->ac_model->del_task($inc_m['id']);
				}
			}
			//
			//запуск контроллера
			//
			elseif ($inc_m['operation'] == 'power_on') {
				$out_m = [];
				$out_m['id'] = 0;
				$out_m['operation'] = 'set_active';
				$out_m['active'] = $active;
				$out_m['online'] = $online;
				$out_msg['messages'][] = $out_m;

				$data = [
					'fw' => $inc_m['fw'],
					'conn_fw' => $inc_m['conn_fw'],
					'ip' => $inc_m['controller_ip']
				];
				$this->db->where('id', $controller_id);
				$this->db->update('controllers', $data);
			}
			//
			//проверка доступа
			//
			elseif ($inc_m['operation'] == 'check_access') {
				$out_m['id'] = $inc_m['id']; //запись верна, т.к. ответ должен быть с тем же id
				$out_m['operation'] = 'check_access';
				$out_m['granted'] = 0;

				$this->db->where('wiegand', $inc_m['card']);
				$query = $this->db->get('cards');

				if ($query->num_rows() > 0) {
					$card_id = $query->row()->id;

					if ($query->row()->holder_id != -1) {
						$out_m['granted'] = 1;
					}

					$this->ac_model->set_card_last_conn($card_id, $controller_id);
				} else {
					$data = [
						'wiegand' => $inc_m['card'],
						'last_conn' => $time,
						'controller_id' => $controller_id,
						'holder_id' => -1
					];
					$this->db->insert('cards', $data);
				}
				$out_msg['messages'][] = $out_m;
			}
			//
			//пинг от контроллера
			//
			elseif ($inc_m['operation'] == 'ping') {
				//do nothing
			}
			//
			//события на контроллере
			//
			elseif ($inc_m['operation'] == 'events') {
				$events_count = 0;
				$events = [];

				//чтение событий
				foreach ($inc_m['events'] as $event) {
					$event_time = DateTime::createFromFormat('Y-m-d H:i:s', $event['time']);
					$event_time = $event_time->getTimestamp();

					$this->db->where('wiegand', $event['card']);
					$query = $this->db->get('cards');
					//проверяем наличие карты в БД
					if ($query->num_rows() > 0) {
						$card_id = $query->row()->id;

						$this->ac_model->set_card_last_conn($card_id, $controller_id);
					} else {
						$data = [
							'wiegand' => $event['card'],
							'last_conn' => $time,
							'controller_id' => $controller_id,
							'holder_id' => -1
						];
						$this->db->insert('cards', $data);

						$card_id = $this->db->insert_id();
					}

					$events[] = [
						'controller_id' => $controller_id,
						'event' => $event['event'],
						'flag' => $event['flag'],
						'time' => $event_time,
						'server_time' => $time,
						'card_id' => $card_id
					];

					$events_count++;
				}
				$this->db->insert_batch('events', $events);

				$out_m['id'] = $inc_m['id'];
				$out_m['operation'] = 'events';
				$out_m['events_success'] = $events_count;
				$out_msg['messages'][] = $out_m;
			}
		}

		//запрос заданий из БД
		$task = $this->ac_model->get_last_task($controller_id);

		if ($task) {
			$out_msg['messages'][] = json_decode($task->json);
		}

		$out_json_msg = json_encode($out_msg);
		echo $out_json_msg;

		$path = "$LOG_PATH/inc-$log_date.txt";
		write_file($path, "TYPE: $type || SN: $sn || $inc_json_msg\n", 'a');

		$path = "$LOG_PATH/out-$log_date.txt";

		write_file($path, "TYPE: $type || SN: $sn || $out_json_msg\n", 'a');
	}

	/*private function _wiegand_to_EM($str)
	{
		$a = hexdec(substr($str, -6, 2)); //000000>>AB<<ABAB -->> 171
		$b = hexdec(substr($str, -4)); //000000AB>>ABAB<< -->> 43947

		$a = _charAdd($a, 3); //171 -->> '171'
		$b = _charAdd($a, 6); //43947 -->> '043947'

		return $a.','.$b;
	}

	private function _char_add($str, $l)
	{
		while (strlen($str) < $l) {
			$str = substr_replace($str, '0', 0);
		}
		return $str;
	}*/
}

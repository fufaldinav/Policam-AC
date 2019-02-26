<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use WebSocket\Client;

class Server extends CI_Controller {

	public function __construct()	{
		parent::__construct();

		$this->load->model('ac_model');
	}

	public function index() {
		$this->load->helper('file');
		$this->load->helper('date');

		$json_message = file_get_contents('php://input');

		$datestring = '%Y-%m-%d %H:%i:%s';
		$time = now('Asia/Yekaterinburg');
		$json_data['date'] = mdate($datestring, $time);
		$datestring = '%Y-%m-%d';
		$date = mdate($datestring, $time);
		$json_data['interval'] = 10; //интервал обновления данных
		$json_data['messages'] = [];

		$json_array = json_decode($json_message, TRUE);

		//запрос контроллера по серийнику из БД
		$query = $this->db->get_where('controllers', Array('sn' => $json_array['sn']), 1, 0);

		//поиск контроллера в базе
		if ($query->num_rows() > 0) {
			$active = $query->row()->active;
			$online = $query->row()->online;
			$c_id = $query->row()->id;

			//время последнего соединения контроллера
			$data = Array(
							'last_conn' => $time
			);
			$this->db->where('id', $c_id);
			$this->db->update('controllers', $data);
		}
		else {
			$path = '/var/www/logs';

			if (!is_dir($path)) {
				mkdir($path, 0777, TRUE);
			}

			$path .= '/inc-';
			$path .= $date;
			$path .= '.txt';

			$message = 'TYPE: ';
			$message .= $json_array['type'];
			$message .= ' || SN: ';
			$message .= $json_array['sn'];
			$message .= ' || Неизвестный контроллер';
			$message .= PHP_EOL;

			write_file($path, $message, 'a');
			return FALSE;
		}

		header('Content-Type: application/json');

		//чтение json сообщения
		foreach ($json_array['messages'] as $json_m) {
			//простой ответ
			if (!isset($json_m['operation']) && isset($json_m['success'])) {
				if ($json_m['success'] == 1) {
					$this->ac_model->del_task($json_m['id']);
				}
			}
			//запуск контроллера
			elseif ($json_m['operation'] == 'power_on') {
				$m['id'] = 0;
				$m['operation'] = 'set_active';
				$m['active'] = $active;
				$m['online'] = $online;
				$json_data['messages'][] = $m;

				//запишем ip-адресс контролера
				$data = Array(
								'fw' => $json_m['fw'],
								'conn_fw' => $json_m['conn_fw'],
								'ip' => $json_m['controller_ip']
				);
				$this->db->where('id', $c_id);
				$this->db->update('controllers', $data);
			}
			//проверка доступа
			elseif ($json_m['operation'] == 'check_access') {
				$m['id'] = $json_m['id'];
				$m['operation'] = 'check_access';
				$query = $this->db->get_where('cards', Array('wiegand' => $json_m['card']), 1, 0);

				if ($query->num_rows() > 0) {
					$card_id = $query->row()->id;

					if ($query->row()->holder_id == -1)
					{
						$m['granted'] = 0;
						//$this->_send_data($query->row(), 1);
					}
					else {
						$m['granted'] = 1;
					}

					$this->_set_card_last_conn($card_id, $c_id);

				}	else {
					$data = Array(
									'wiegand' => $json_m['card'],
									'last_conn' => $time,
									'controller_id' => $c_id,
									'holder_id' => -1
					);
					$this->db->insert('cards', $data);
					$data['id'] = $this->db->insert_id();

					$m['granted'] = 0;

					//$this->_send_data($data, 1);
				}
				$json_data['messages'][] = $m;
			}
			//пинг от контроллера
			elseif ($json_m['operation'] == 'ping') {
				//do nothing
			}
			//события на контроллере
			elseif ($json_m['operation'] == 'events') {
				$event_count = 0;
				$event_data = Array();

				//чтение событий
				foreach ($json_m['events'] as $event) {
					$event_time = DateTime::createFromFormat('Y-m-d H:i:s', $event['time']);
					$event_time = $event_time->getTimestamp();

					$query = $this->db->get_where('cards', Array('wiegand' => $event['card']), 1, 0);
					//проверяем наличие карты в БД
					if ($query->num_rows() > 0) {
						$card_id = $query->row()->id;
						$card_holder_id = $query->row()->holder_id;

						$this->_set_card_last_conn($card_id, $c_id);

					} else {
						$card_holder_id = -1;

						$data = Array(
										'wiegand' => $event['card'],
										'last_conn' => $time,
										'controller_id' => $c_id,
										'holder_id' => $card_holder_id
						);
						$this->db->insert('cards', $data);

						$card_id = $this->db->insert_id();

					}

					$client_data = Array(
						'controller_id' => $c_id,
						'event' => $event['event'],
						'flag' => $event['flag'],
						'time' => $event_time,
						'server_time' => now('Asia/Yekaterinburg'),
						'card_id' => $card_id
					);
					$event_data[] = $client_data;

					/*if ($event['event'] == 0 || $event['event'] == 1 || $event['event'] == 4 || $event['event'] == 5) {//TODO проверить эвент
						$this->db->where('cards.id', $card_id);
						$this->db->from('cards');
						$this->db->join('personal', 'personal.id = cards.holder_id', 'left');
						$query = $this->db->get();

						$this->_send_data(Array(
							'event' => $client_data,
							'user' => $query->row()
						), 2);
					}*/

					$event_count++;
				}
				$this->db->insert_batch('events', $event_data);

				$m['id'] = $json_m['id'];
				$m['operation'] = 'events';
				$m['events_success'] = $event_count;
				$json_data['messages'][] = $m;
			}
		}

		//запрос заданий из БД
		$task = $this->ac_model->get_last_task($c_id);

		if ($task) {
			$json_data['messages'][] = json_decode($task->json);
		}

		echo json_encode($json_data);

		$path = '/var/www/logs';

		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}

		$path .= '/inc-';
		$path .= $date;
		$path .= '.txt';

		$message = 'TYPE: ';
		$message .= $json_array['type'];
		$message .= ' || SN: ';
		$message .= $json_array['sn'];
		$message .= ' || ';
		$message .= $json_message;
		$message .= PHP_EOL;

		write_file($path, $message, 'a');

		$path = '/var/www/logs';

		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}

		$path .= '/out-';
		$path .= $date;
		$path .= '.txt';

		$message = 'TYPE: ';
		$message .= $json_array['type'];
		$message .= ' || SN: ';
		$message .= $json_array['sn'];
		$message .= ' || ';
		$message .= json_encode($json_data);
		$message .= PHP_EOL;

		write_file($path, $message, 'a');
	}

	/*private function _send_data($data, $msg_type = 0, $user_id = 0)	{
		//$client = new Client("ws://192.168.1.9:8081/ws");
		//$client->send(json_encode($data));
		$sql_data = Array(
			'user_id' => $user_id,
			'time' => now('Asia/Yekaterinburg'),
			'msg_type' => $msg_type,
			'json' => json_encode($data)
		);
		$this->db->insert('messages', $sql_data);
	}*/

	private function _set_card_last_conn($card_id, $c_id) {
		$data = Array(
						'last_conn' => now('Asia/Yekaterinburg'),
						'controller_id' => $c_id
		);
		$this->db->where('id', $card_id);
		$this->db->update('cards', $data);
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

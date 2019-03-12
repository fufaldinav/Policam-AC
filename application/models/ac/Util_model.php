<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 02.03.2019
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
 * Class Util Model
 * @property Ctrl_model $ctrl
 * @property Org_model $org
 */
class Util_model extends CI_Model
{
	/**
	 * Таймаут одного long poll
	 *
	 * @var int $timeout
	 */
	private $timeout;

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

		$this->log_path = $this->config->item('log_path', 'ac');

		if (!is_dir($this->log_path)) {
			mkdir($this->log_path, 0755, true);
		}

		$this->timeout = $this->config->item('long_poll_timeout', 'ac');
	}

	/**
	 * Реализует long polling
	 *
	 * @param int|null $time   Время последнего запроса
	 * @param int[]    $events ID событий
	 *
	 * @return mixed[] События от контроллера
	 */
	public function start_polling(int $time = null, array $events): array
	{
		$this->load->model('ac/org_model', 'org');
		$this->load->model('ac/ctrl_model', 'ctrl');

		$time = $time ?? now('Asia/Yekaterinburg');

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$orgs = $this->org->get_all($user_id); //TODO

		$ctrls = [];

		foreach ($orgs as $org) {
			$ctrls = array_merge($ctrls, $this->ctrl->get_all($org->id));
		}

		if (count($ctrls) > 0) {
			session_write_close();
			set_time_limit(0);

			$timer = $this->timeout;
			while ($timer > 0) {
				$ctrl_ids = [];
				foreach ($ctrls as $ctrl) {
					$ctrl_ids[] = $ctrl->id;
				}
				$query = $this->db
					->where('server_time >', $time)
					->where_in('controller_id', $ctrl_ids)
					->where_in('event', $events)
					->order_by('time', 'DESC')
					->get('events');

				if ($query->num_rows() > 0) {
					$response = [];

					foreach ($query->result() as $row) {
						$response[] = $row;
					}

					return $response;
				}

				$timer--;
				sleep(1);
			}
		} else {
			return [];
		}

		return [];
	}

	/**
	 * Сохраняет полученное от пользователя событие
	 *
	 * @param int    $user_id ID пользователя
	 * @param int    $type    Тип события
	 * @param string $desc    Описание события
	 *
	 * @return int Количество успешных сохранений
	 */
	public function add_user_event(int $user_id, int $type, string $desc): int
	{
		$data =	[
			'user_id' => $user_id,
			'type' => $type,
			'description' => $desc,
			'time' => now('Asia/Yekaterinburg')
		];

		$this->db->insert('users_events', $data);

		return $this->db->affected_rows();
	}

	/**
	 * Сохраняет ошибки
	 *
	 * @param string $err Текст ошибки
	 */
	public function save_errors(string $err): void
	{
		$this->load->helper('file');

		$time = now('Asia/Yekaterinburg');
		$datestring = '%Y-%m-%d';
		$date = mdate($datestring, $time);
		$timestring = '%H:%i:%s';
		$time = mdate($timestring, $time);

		$path = $this->log_path . '/err-' . $date . '.txt';
		write_file($path, "$time $err\n", 'a');
	}
}

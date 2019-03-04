<?php
/**
 * Name:   Util Model
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 02.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.0 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
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

		$this->log_path = $this->config->item('log_path', 'ac');

		if (!is_dir($this->log_path)) {
			mkdir($this->log_path, 0755, true);
		}

		$this->timeout = $this->config->item('long_poll_timeout', 'ac');
	}

	/**
	 * Реализация long polling
	 *
	 * @property Org_model $org
	 * @property Ctrl_model $ctrl
	 * @param int $time Время последнего запроса
	 * @param int[] $events ID событий
	 * @return mixed[]
	 */
	public function start_polling($time, $events)
	{
		$this->load->model('ac/org_model', 'org');
		$this->load->model('ac/ctrl_model', 'ctrl');

		if (!is_numeric($time)) {
			$time = now('Asia/Yekaterinburg');
		}

		$user_id = $this->ion_auth->user()->row()->id; //TODO

		$orgs = $this->org->get_all($user_id);

		$ctrls = [];

		foreach ($orgs as $org) {
			$ctrls = array_merge($ctrls, $this->ctrl->get_all($org->id));
		}

		if ($ctrls) {
			session_write_close();
			set_time_limit(0);

			$timer = $this->timeout;
			while ($timer > 0) {
				$c_ids = [];
				foreach ($ctrls as $c) {
					$c_ids[] = $c->id;
				}
				$this->db->where_in('controller_id', $c_ids);
				$this->db->order_by('time', 'DESC');
				$this->db->where_in('event', $events);
				$this->db->where('server_time >', $time);
				$query = $this->db->get('events');

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
	 * Рендер имени организации
	 *
	 * @param int $org_id ID организации
	 * @return string Строка в формате 'номер (адресс при наличии)'
	 */
	public function render_org_name($org_id)  //TODO check
	{
		$org = $this->org->get($org_id);

		$org_name = $org->number;
		if ($org->address) {
			$org_name .= ' (';
			$org_name .= $org->address;
			$org_name .= ')';
		}

		return $org_name;
	}

	/**
	 * Рендер строки подключения CSS
	 *
	 * @param string[] $arr Список имен CSS файлов
	 * @return string
	 */
	public function render_css($arr)
	{
		$result = '';

		foreach ($arr as $str) {
			$result .= '<link rel="stylesheet" href="/css/';
			$result .= $str;
			$result .= '.css" />';
		}

		return $result;
	}

	/**
	 * Рендер строки подключения JavaScript
	 *
	 * @param string[] $arr Список имен JS файлов
	 * @return string
	 */
	public function render_js($arr)
	{
		$result = '<script src="/js/jquery-3.3.1.min.js"></script>';

		foreach ($arr as $str) {
			switch ($str) {
				default:
					$str .= '-0.0.5';
					break;
			}

			$result .= '<script src="/js/ac/';
			$result .= $str;
			$result .= '.js"></script>';
		}

		return $result;
	}

	/**
	 * Рендер навигационных кнопок
	 *
	 * @return string
	 */
	public function render_nav()
	{
		$html = '<a class="nav" href="/">';
		$html .= lang('observ');
		$html .= '</a>';

		if ($this->ion_auth->in_group(2)) {
			$html .= '<a class="nav" href="/ac/add_person">';
			$html .= lang('adding');
			$html .= '</a>';
			$html .= '<a class="nav" href="/ac/edit_persons">';
			$html .= lang('editing');
			$html .= '</a>';
			$html .= '<a class="nav" href="/ac/classes">';
			$html .= lang('classes');
			$html .= '</a>';
		}

		return $html;
	}

	/**
	 * Сохранение полученого от пользователя события
	 *
	 * @param int $type    Тип события
	 * @param string $desc Описание события
	 * @return int
	 */
	public function add_user_event($type, $desc)
	{
		$user_id = $this->ion_auth->user()->row()->id; //TODO

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
	 * Сохранение ошибок
	 *
	 * @param string $err Текст ошибки
	 */
	public function save_errors($err)
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

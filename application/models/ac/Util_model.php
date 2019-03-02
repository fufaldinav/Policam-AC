<?php
/**
 * Name:    Util Model
 * Author:  Artem Fufaldin
 *          artem.fufaldin@gmail.com
 *
 * Created:  02.03.2019
 *
 * Description:  Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.0 or above
 *
 * @package    Policam-AC
 * @author     Artem Fufaldin
 * @link       http://github.com/m2jest1c/Policam-AC
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Util Model
 */
class Util_model extends CI_Model
{
	/**
	* @var int
	*/
	const TIMER = 10;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Реализация long polling
	 *
	 * @property  Organization_model  $organization
	 * @property  Controller_model    $controller
	 * @param  int    $time
	 * @param  int[]  $events
	 * @return  mixed[]
	 */
	public function start_polling($time, $events)
	{
		$this->load->model('ac/organization_model', 'organization');
		$this->load->model('ac/controller_model', 'controller');

		if (!is_numeric($time)) {
			$time = now('Asia/Yekaterinburg');
		}

		$user_id = $this->ion_auth->user()->row()->id;

		$organizations = $this->organization->get_all($user_id);

		$controllers = [];

		foreach ($organizations as $org) {
			$controllers = array_merge($controllers, $this->controller->get_all($org->id));
		}

		if ($controllers) {
			session_write_close();
			set_time_limit(0);

			$timer = self::TIMER;
			while ($timer > 0) {
				$c_ids = [];
				foreach ($controllers as $c) {
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
	 * @param   int     $org_id
	 * @return  string  Строка в формате 'N (адресс при наличии)'
	 */
	public function render_org_name($org_id)
	{ //TODO check
		$this->db->where('id', $org_id);
		$query = $this->db->get('organizations');

		$org_name = $query->row()->number;
		if ($query->row()->address) {
			$org_name .= ' (';
			$org_name .= $query->row()->address;
			$org_name .= ')';
		}

		return $org_name;
	}

	/**
	 * Рендер строки подключения CSS
	 *
	 * @param   string[]  $arr
	 * @return  string
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
	 * @param   string[]  $arr
	 * @return  string
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
	 * @return  string
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
	 * @param   int     $type  Тип события
	 * @param   string  $desc  Описание события
	 * @return  int
	 */
	public function add_user_event($type, $desc)
	{
		$data =	[
			'user_id' => $this->user_id,
			'type' => $type,
			'description' => $desc,
			'time' => now('Asia/Yekaterinburg')
		];

		$this->db->insert('users_events', $data);

		return $this->db->affected_rows();
	}
}

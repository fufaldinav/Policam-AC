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
	private $user_id;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Реализация long polling
	 *
	 * @return  mixed[]
	 */
	public function start_polling()
	{
		$events = $this->input->post('events');
		$time = $this->input->post('time');

		if (!is_numeric($time)) {
			$time = now('Asia/Yekaterinburg');
		}

		$org_id = $this->get_org_by_user($this->user_id)->org_id;

		$controllers = $this->get_controllers_by_org($org_id);

		if ($controllers) {
			session_write_close();
			set_time_limit(0);

			$timer = 10;
			while ($timer > 0) {
				foreach ($controllers as $c) {
					$c_array[] = $c->id;
				}
				$this->db->where_in('controller_id', $c_array);
				$this->db->order_by('time', 'DESC');
				$this->db->where_in('event', $events);
				$this->db->where('server_time >', $time);
				$query = $this->db->get('events');

				if ($query->num_rows() > 0) {
					$msgs = [];
					foreach ($query->result() as $row) {
						$msgs[] = $row;
					}

					return $msgs;
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

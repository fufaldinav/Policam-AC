<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ac_model extends CI_Model {
	private $user_id;

	public function __construct()	{
		parent::__construct();

		if ($this->ion_auth->logged_in()) {
			$this->user_id = $this->ion_auth->user()->row()->id;
		}
	}


	public function get_pers($pers_id) {
		$this->db->select('address, birthday, f, i, o, phone');
		$this->db->select('personal.id AS \'id\'');
		$this->db->select('class_id AS \'class\'');
		$this->db->select('photo.hash AS \'photo\'');
		$this->db->where('personal.id', $pers_id);
		$this->db->join('photo', 'photo.id = personal.photo_id', 'left');
		$query = $this->db->get('personal');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return NULL;
		}
	}

	public function get_school_by_user($user_id) {
		$this->db->where('users.id', $user_id);
		$this->db->join('schools', 'schools.id = users.school_id', 'inner');
		$query = $this->db->get('users');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_pers_by_card($card_id) {
		$this->db->select('address, birthday, f, i, o, phone');
		$this->db->select('personal.id AS \'id\'');
		$this->db->select('class_id AS \'class\'');
		$this->db->select('photo.hash AS \'photo\'');
		$this->db->where('cards.id', $card_id);
		$this->db->join('personal', 'personal.id = cards.holder_id', 'inner');
		$this->db->join('photo', 'photo.id = personal.photo_id', 'left');
		$query = $this->db->get('cards');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_personal_by_class($class_id, $full_info = FALSE) {
			if ($full_info === TRUE) {
				$select = '*';
			} else {
				$select = 'f, i, o';
			}
			$this->db->select($select);
			$this->db->select('personal.id AS "id"');
			$this->db->join('personal', 'personal.class_id = classes.id', 'left');
			$this->db->where('classes.id', $class_id);
			$query = $this->db->get('classes');

			return $query->result();
	}

	public function get_class_by_id($class_id) {
		$this->db->where('id', $class_id);
		$query = $this->db->get('classes');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_classes_by_school($school_id) {
		$this->db->where('school_id', $school_id);
		$this->db->order_by('number ASC, letter ASC');
		$query = $this->db->get('classes');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function get_controllers_by_school($school_id) {
		$this->db->where('school_id', $school_id);
		$query = $this->db->get('controllers');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function get_pers_and_cards_by_school($school_id) {
		$this->db->select('number, letter, f, i, o');
		$this->db->select('personal.id AS \'pers_id\'');
		$this->db->select('cards.id AS \'card_id\'');
		$this->db->where('classes.school_id', $school_id);
		$this->db->from('classes');
		$this->db->join('personal', 'personal.class_id = classes.id', 'left');
		$this->db->join('cards', 'cards.holder_id = personal.id', 'left');
		$this->db->group_by('pers_id'); //чтобы не дублировались записи с несколькими ключами
		$this->db->order_by('number ASC, letter ASC, f ASC, i ASC');
		$query = $this->db->get()->result();



		if (count($query) > 0) {
			//echo $this->db->last_query();
			$classes = [];

			foreach ($query as $row) {
				$classes[$row->number.$row->letter][] = $row; //number + letter для сортировки дерева 1А -> 1Б -> 2А etc.
			}

			ksort($classes);

			return $classes;
		} else {
			return FALSE;
		}
	}

	public function get_cards($holder_id = -1, $controller_id = NULL) {
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}

		$this->db->select('id, wiegand, holder_id');
		$this->db->where('holder_id', $holder_id);
		if ($controller_id) {
			$this->db->where('controller_id', $controller_id);
		}
		$query = $this->db->get('cards');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function add_card($card_id) {
		$this->db->select('wiegand, controller_id');
		$this->db->where('id', $card_id);
		$query = $this->db->get('cards');

		$school_id = $this->get_school_by_user($this->user_id)->school_id;
		$controllers = $this->get_controllers_by_school($school_id);

		$wiegand = $query->row()->wiegand;

		foreach ($controllers as $c) {
			$this->add_cards_to_controller($wiegand, $c->id);
		}

		return TRUE;
	}

	public function delete_card($card_id) {
		$school_id = $this->ac_model->get_school_by_user($this->user_id)->school_id;
		$controllers = $this->ac_model->get_controllers_by_school($school_id);

		$this->db->where('id', $card_id);
		$wiegand = $this->db->get('cards')->row()->wiegand;

		$this->db->where('id', $card_id);
		$this->db->update('cards', ['holder_id' => -1]);

		if ($this->db->affected_rows()) {
			foreach ($controllers as $c) {
				$this->ac_model->del_cards_from_controller($wiegand, $c->id);
			}
			return TRUE;
		} else {
			return NULL;
		}
	}

	public function start_polling() {
		$events = $this->input->post('events');
		$time = $this->input->post('time');

		if (!is_numeric($time)) {
			$time = now('Asia/Yekaterinburg');
		}

		$school_id = $this->get_school_by_user($this->user_id)->school_id;

		$controllers = $this->get_controllers_by_school($school_id);

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

	public function delete_photo($personal_id = NULL, $photo_hash = NULL) {
		if (!$personal_id) {
			$this->db->select('personal.id AS "id"');
			$this->db->where('photo.hash', $photo_hash);
			$this->db->join('personal', 'personal.photo_id = photo.id', 'left');
			$query = $this->db->get('photo');

			$personal_id = $query->row()->id;
		}

		if (!$photo_hash) {
			$this->db->select('hash');
			$this->db->where('personal.id', $personal_id);
			$this->db->join('photo', 'photo.id = personal.photo_id', 'left');
			$query = $this->db->get('personal');

			$photo_hash = $query->row()->hash;
		}

		$this->db->where('id', $personal_id);
		$this->db->update('personal', ['photo_id' => NULL]);

		$this->db->delete('photo', ['hash' => $photo_hash]);

		try {
			$file_path = '/var/www/img_ac/';

			$file_path_b = $file_path;
			$file_path_b .= $photo_hash;
			$file_path_b .= '.jpg';

			if (file_exists($file_path_b)) {
				unlink($file_path_b);
			}

			$file_path_s = $file_path;
			$file_path_s .= 's/';
			$file_path_s .= $photo_hash;
			$file_path_s .= '.jpg';

			if (file_exists($file_path_s)) {
				unlink($file_path_s);
			}

			return TRUE;
		} catch (Exception $e) {
			$this->save_js_errors($e);
			return FALSE;
		}
	}

	public function render_school_name($school_id) { //TODO check
		$this->db->where('id', $school_id);
		$query = $this->db->get('schools');

		$school = $query->row()->number;
		if ($query->row()->address)
		{
			$school .= ' (';
			$school .= $query->row()->address;
			$school .= ')';
		}

		return $school;
	}

	public function render_css($arr) {
		$result = '';

		foreach ($arr as $str) {
			$result .= '<link rel="stylesheet" href="/css/';
			$result .= $str;
			$result .= '.css" />';
		}

		return $result;
	}

	public function render_js($arr) {
		$result = '<script src="/js/jquery-3.3.1.min.js"></script>';

		foreach ($arr as $str) {
			switch ($str) {
				case 'main':
					$str .= '-0.0.5';
					break;

				case 'edit_pers':
					$str .= '-0.0.5';
					break;

				default:
					$str .= '-0.0.4';
					break;
			}

			$result .= '<script src="/js/ac/';
			$result .= $str;
			$result .= '.js"></script>';
		}

		return $result;
	}

	public function render_nav() {
		$html = '<a class="nav" href="/">';
		$html .= lang('observ');
		$html .= '</a>';

		if ($this->ion_auth->in_group(2)) {
			$html .= '<a class="nav" href="/ac/add_pers">';
			$html .= lang('adding');
			$html .= '</a>';
			$html .= '<a class="nav" href="/ac/edit_pers">';
			$html .= lang('editing');
			$html .= '</a>';
			$html .= '<a class="nav" href="/ac/classes">';
			$html .= lang('classes');
			$html .= '</a>';
		}

		return $html;
	}

	public function add_all_cards_to_controller($controller_id) {
		$this->db->select('cards.wiegand AS "wiegand"');
		$this->db->where('controllers.id', $controller_id);
		$this->db->join('schools', 'schools.id = controllers.school_id', 'left');
		$this->db->join('classes', 'classes.school_id = schools.id', 'left');
		$this->db->join('personal', 'personal.class_id = classes.id', 'left');
		$this->db->join('cards', 'cards.holder_id = personal.id', 'left');
		$query = $this->db->get('controllers');

		$cards = $query->result();

		$count = count($cards);
		$counter = 0;
		$data = [];
		for ($i = 0; $i < $count; $i++) {
			$data[] = $cards[$i]->wiegand;
			if ($i > 0 && ($i % 10) == 0) {
				$counter += $this->add_cards_to_controller($data, $controller_id);
				$data = [];
			} else if ($i == ($count - 1)) {
				$counter += $this->add_cards_to_controller($data, $controller_id);
			}
		}

		return $counter;
	}

	public function add_cards_to_controller($cards, $controller_id) {
		$data = '"cards": [';
		if (is_array($cards)) {
			foreach ($cards as $card) {
				$data .= '{"card":"';
				$data .= $card;
				$data .= '","flags":32,"tz":255},';
			}
			$data = substr($data, 0, -1);
		} else {
			$data .= '{"card":"';
			$data .= $cards;
			$data .= '","flags":32,"tz":255}';
		}
		$data .= ']';
		return $this->add_task('add_cards', $controller_id, $data);
	}

	public function del_cards_from_controller($cards, $controller_id) {
		$data = '"cards": [';
		if (is_array($cards)) {
			foreach ($cards as $card) {
				$data .= '{"card":"';
				$data .= $card;
				$data .= '"},';
			}
			$data = substr($data, 0, -1);
		} else {
			$data .= '{"card":"';
			$data .= $cards;
			$data .= '"}';
		}
		$data .= ']';
		return $this->add_task('del_cards', $controller_id, $data);
	}

	public function clear_cards($controller_id) {
		return $this->add_task('clear_cards', $controller_id, $data);
	}

	public function set_door_params($controller_id, $open_time, $open_control = 0, $close_control = 0) {
		$data = '"open":';
		$data .= $open_time;
		$data .= ',"open_control":';
		$data .= $open_control;
		$data .= ',"close_control":';
		$data .= $close_control;

		return $this->add_task('set_door_params', $controller_id, $data);
	}

	public function add_task($operation, $controller_id, $data = NULL) {
		$id = mt_rand(500000,999999999);

		$json = '{"id":';
		$json .= $id;
		$json .= ',"operation":"';
		$json .= $operation;
		$json .= '"';
		$json .= (isset($data)) ? ',' : '';
		$json .= (isset($data)) ? $data : '';
		$json .= '}';

		$data =	[
							'id' => $id,
							'controller_id' => $controller_id,
							'json' => $json,
							'time' => now('Asia/Yekaterinburg')
						];

		$this->db->insert('tasks', $data);

		return $this->db->affected_rows();
	}

	public function del_task($id) {
		$this->db->where('id', $id);
		$this->db->delete('tasks');

		return $this->db->affected_rows();
	}

	public function get_last_task($controller_id) {
		$this->db->where('controller_id', $controller_id);
		$this->db->order_by('time', 'ASC');
		$query = $this->db->get('tasks');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return NULL;
		}
	}

	public function add_user_event($type, $desc) {
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

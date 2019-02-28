<?php defined('BASEPATH') or exit('No direct script access allowed');

class Ac_model extends CI_Model
{
	/**
	* @var int
	*/
	private $user_id;

	public function __construct()
	{
		parent::__construct();

		if ($this->ion_auth->logged_in()) {
			$this->user_id = $this->ion_auth->user()->row()->id;
		}
	}

	/**
	 * Получение информации о человеке
	 *
	 * @param   int           $person_id
	 * @return  mixed[]|null
	 */
	public function get_person($person_id)
	{
		$this->db->select('address, birthday, f, i, o, phone');
		$this->db->select("persons.id AS 'id'");
		$this->db->select("div_id AS 'div'");
		$this->db->select("photo.hash AS 'photo'");
		$this->db->where('persons.id', $person_id);
		$this->db->join('photo', 'photo.id = persons.photo_id', 'left');
		$query = $this->db->get('persons');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	 * Получение информации о организации, к которой привязан пользователь
	 *
	 * @param   int           $user_id
	 * @return  mixed[]|null
	 */
	public function get_org_by_user($user_id)
	{
		$this->db->where('users.id', $user_id);
		$this->db->join('organizations', 'organizations.id = users.org_id', 'inner');
		$query = $this->db->get('users');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	 * Получение информации о человеке, к которому привязана карта
	 *
	 * @param   int           $card_id
	 * @return  mixed[]|null
	 */
	public function get_person_by_card($card_id)
	{
		$this->db->select('address, birthday, f, i, o, phone');
		$this->db->select("persons.id AS 'id'");
		$this->db->select("div_id AS 'div'");
		$this->db->select("photo.hash AS 'photo'");
		$this->db->where('cards.id', $card_id);
		$this->db->join('persons', 'persons.id = cards.holder_id', 'inner');
		$this->db->join('photo', 'photo.id = persons.photo_id', 'left');
		$query = $this->db->get('cards');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	 * Получение списка людей из подразделения
	 *
	 * @param   int      $div_id
	 * @param   bool     $full_info
	 * @return  mixed[]
	 */
	public function get_persons_by_div($div_id, $full_info = null)
	{
		if ($full_info === true) {
			$this->db->select('*');
		} else {
			$this->db->select('f, i, o');
		}
		$this->db->select('persons.id AS "id"');
		$this->db->join('persons', 'persons.div_id = divisions.id', 'left');
		$this->db->where('divisions.id', $div_id);
		$this->db->order_by('f ASC, i ASC, o ASC');
		$query = $this->db->get('divisions');

		return $query->result();
	}

	/**
	 * Получение информации о подразделении
	 *
	 * @param   int           $div_id
	 * @return  mixed[]|null
	 */
	public function get_div_by_id($div_id)
	{
		$this->db->where('id', $div_id);
		$query = $this->db->get('divisions');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	 * Получение информации о подразделениях организации
	 *
	 * @param   int           $org_id
	 * @return  mixed[]|null
	 */
	public function get_divisions_by_org($org_id)
	{
		$this->db->where('org_id', $org_id);
		$this->db->order_by('number ASC, letter ASC');
		$query = $this->db->get('divisions');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	 * Получение информации о контроллерах организации
	 *
	 * @param   int           $org_id
	 * @return  mixed[]|null
	 */
	public function get_controllers_by_org($org_id)
	{
		$this->db->where('org_id', $org_id);
		$query = $this->db->get('controllers');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	 * Получение списка людей и привязаных к ним карт из организации
	 *
	 * @param   int           $org_id
	 * @return  mixed[]|null
	 */
	public function get_persons_and_cards_by_org($org_id) //TODO переработать
	{
		$this->db->select('number, letter, f, i, o');
		$this->db->select("persons.id AS 'id'");
		$this->db->select("cards.id AS 'card_id'");
		$this->db->where('divisions.org_id', $org_id);
		$this->db->join('persons', 'persons.div_id = divisions.id', 'left');
		$this->db->join('cards', 'cards.holder_id = persons.id', 'left');
		$this->db->group_by('id'); //чтобы не дублировались записи с несколькими ключами
		$this->db->order_by('number ASC, letter ASC, f ASC, i ASC');
		$query = $this->db->get('divisions');



		if ($query->num_rows() > 0) {
			$divisions = [];

			foreach ($query->result() as $row) {
				$divisions[$row->number.$row->letter][] = $row; //number + letter для сортировки дерева 1А -> 1Б -> 2А etc.
			}

			ksort($divisions);

			return $divisions;
		} else {
			return null;
		}
	}

	/**
	 * Получение списка карт, привязаных к конкретному человеку или все неизвестные карты
	 *
	 * @param   int           $holder_id  Опционально, по-умолчанию -1 (список всех неизвестных карт)
	 * @return  mixed[]|null
	 */
	public function get_cards($holder_id = -1)
	{
		$this->db->select('id, wiegand, holder_id');
		$this->db->where('holder_id', $holder_id);
		$query = $this->db->get('cards');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	 * Добавление карты в память контроллеров
	 *
	 * @param   int   $card_id
	 * @return  int
	 */
	public function add_card($card_id)
	{
		$this->db->select('wiegand, controller_id');
		$this->db->where('id', $card_id);
		$query = $this->db->get('cards');

		$org_id = $this->get_org_by_user($this->user_id)->org_id;
		$controllers = $this->get_controllers_by_org($org_id);

		$wiegand = $query->row()->wiegand;

		$counter = 0;
		foreach ($controllers as $c) {
			$counter += $this->add_cards_to_controller($wiegand, $c->id);
		}

		return $counter;
	}

	/**
	 * Удаление карты из памяти контроллеров
	 *
	 * @param   int   $card_id
	 * @return  bool
	 */
	public function delete_card($card_id)
	{
		$org_id = $this->ac_model->get_org_by_user($this->user_id)->org_id;
		$controllers = $this->ac_model->get_controllers_by_org($org_id);

		$this->db->where('id', $card_id);
		$wiegand = $this->db->get('cards')->row()->wiegand;

		$this->db->where('id', $card_id);
		$this->db->update('cards', ['holder_id' => -1]);

		if ($this->db->affected_rows()) {
			foreach ($controllers as $c) {
				$this->ac_model->del_cards_from_controller($wiegand, $c->id);
			}
			return true;
		} else {
			return null;
		}
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
	 * Удаление фото из БД и диска
	 *
	 * @param   int        $person_id   Опционально, если не указан, найти ID по фото
	 * @param   string     $photo_hash  Опционально, если не указан, фото по ID человека
	 * @return  bool|null
	 */
	public function delete_photo($person_id = null, $photo_hash = null)
	{
		if ($person_id === null && $photo_hash === null) {
			return null;
		}

		if ($person_id === null) {
			$this->db->select('persons.id AS "id"');
			$this->db->where('photo.hash', $photo_hash);
			$this->db->join('persons', 'persons.photo_id = photo.id', 'left');
			$query = $this->db->get('photo');

			$person_id = $query->row()->id;
		}

		if ($photo_hash === null) {
			$this->db->select('hash');
			$this->db->where('persons.id', $person_id);
			$this->db->join('photo', 'photo.id = persons.photo_id', 'left');
			$query = $this->db->get('persons');

			$photo_hash = $query->row()->hash;
		}

		$this->db->where('id', $person_id);
		$this->db->update('persons', ['photo_id' => null]);

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

			return true;
		} catch (Exception $e) {
			$this->save_js_errors($e);
			return null;
		}
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
	 * Отправляет все карты (частями максимум 10 карт за раз) в контроллер,
	 * предварительно получив список карт людей, принадлежащих организации определенного контроллера
	 *
	 * @param   int  $controller_id
	 * @return  int  Вернет количество сообщений, отправленных на контроллер
	 */
	public function add_all_cards_to_controller($controller_id)
	{
		$this->db->select('cards.wiegand AS "wiegand"');
		$this->db->where('controllers.id', $controller_id);
		$this->db->join('organizations', 'organizations.id = controllers.org_id', 'left');
		$this->db->join('divisions', 'divisions.org_id = organizations.id', 'left');
		$this->db->join('persons', 'persons.div_id = divisions.id', 'left');
		$this->db->join('cards', 'cards.holder_id = persons.id', 'left');
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
			} elseif ($i == ($count - 1)) {
				$counter += $this->add_cards_to_controller($data, $controller_id);
			}
		}

		return $counter;
	}

	/**
	 * Добавление карт в контроллер
	 *
	 * @param   string[]|string  $cards
	 * @param   int              $controller_id
	 * @return  int
	 */
	public function add_cards_to_controller($cards, $controller_id)
	{
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

	/**
	 * Удаление карт из контроллера
	 *
	 * @param   string[]|string  $cards
	 * @param   int              $controller_id
	 * @return  int
	 */
	public function del_cards_from_controller($cards, $controller_id)
	{
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


	/**
	 * Удаление всех карт из контроллера
	 *
	 * @param   int  $controller_id
	 * @return  int
	 */
	public function clear_cards($controller_id)
	{
		return $this->add_task('clear_cards', $controller_id, $data);
	}

	/**
	 * Установить параметры открытия
	 *
	 * @param   int  $controller_id
	 * @param   int  $open_time      Время открытия в 0.1 сек
	 * @param   int  $open_control   Опционально, контроль открытия в 0.1 сек
	 * @param   int  $close_control  Опционально, контроль закрытия в 0.1 сек
	 * @return  int
	 */
	public function set_door_params($controller_id, $open_time, $open_control = 0, $close_control = 0)
	{
		$data = '"open":';
		$data .= $open_time;
		$data .= ',"open_control":';
		$data .= $open_control;
		$data .= ',"close_control":';
		$data .= $close_control;

		return $this->add_task('set_door_params', $controller_id, $data);
	}

	/**
	 * Добавление задания для отправки на контроллер
	 *
	 * @param   int  $operation      Операция, отправляемая на контроллер
	 * @param   int  $controller_id
	 * @param   int  $data           Опционально, дополнительные данные
	 * @return  int
	 */
	public function add_task($operation, $controller_id, $data = null)
	{
		$id = mt_rand(500000, 999999999);

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

	/**
	 * Удаление задания, отправленного на контроллер
	 *
	 * @param   int  $id
	 * @return  int
	 */
	public function del_task($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('tasks');

		return $this->db->affected_rows();
	}

	/**
	 * Получение последнего задания для отправки на контроллер
	 *
	 * @param   int           $controller_id
	 * @return  mixed[]|bool
	 */
	public function get_last_task($controller_id)
	{
		$this->db->where('controller_id', $controller_id);
		$this->db->order_by('time', 'ASC');
		$query = $this->db->get('tasks');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
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

	/**
	 * Установить время последней связи с картой
	 *
	 * @param  int  $card_id        Тип события
	 * @param  int  $controller_id  Описание события
	 */
	public function set_card_last_conn($card_id, $controller_id)
	{
		$data = [
			'last_conn' => now('Asia/Yekaterinburg'),
			'controller_id' => $controller_id
		];
		$this->db->where('id', $card_id);
		$this->db->update('cards', $data);
	}
}

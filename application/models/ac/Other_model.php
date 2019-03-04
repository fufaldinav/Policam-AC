<?php
/**
 * Name:   Other Model
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
 * Class Other Model
 * @property Ctrl_model $ctrl
 * @property Org_model $org
 */
class Other_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Получение списка людей и привязаных к ним карт из организации
	 *
	 * @param int $org_id ID организации
	 * @return mixed[]|null
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
			$divs = [];

			foreach ($query->result() as $row) {
				$divs[$row->number.$row->letter][] = $row; //number + letter для сортировки дерева 1А -> 1Б -> 2А etc.
			}

			ksort($divs);

			return $divs;
		} else {
			return null;
		}
	}

	/**
	 * Добавление карты в память контроллеров
	 *
	 * @param int $card_id ID карты
	 * @return int
	 */
	public function add_card($card_id)
	{
		$this->load->model('ac/ctrl_model', 'ctrl');
		$this->load->model('ac/org_model', 'org');

		$this->db->select('wiegand, controller_id');
		$this->db->where('id', $card_id);
		$query = $this->db->get('cards');

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$orgs = $this->org->get_all($user_id); //TODO
		$org = array_shift($orgs); //TODO

		$ctrls = $this->ctrl->get_all($org->id);

		$wiegand = $query->row()->wiegand;

		$counter = 0;
		foreach ($ctrls as $c) {
			$counter += $this->ctrl->add_cards($c->id, $wiegand);
		}

		return $counter;
	}

	/**
	 * Удаление карты из памяти контроллеров
	 *
	 * @param int $card_id ID карты
	 * @return bool
	 */
	public function delete_card($card_id)
	{
		$this->load->model('ac/ctrl_model', 'ctrl');
		$this->load->model('ac/org_model', 'org');

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$orgs = $this->org->get_all($user_id); //TODO
		$org = array_shift($orgs); //TODO

		$ctrls = $this->ctrl->get_all($org->id);

		$this->db->where('id', $card_id);
		$wiegand = $this->db->get('cards')->row()->wiegand;

		$this->db->where('id', $card_id);
		$this->db->update('cards', ['holder_id' => -1]);

		if ($this->db->affected_rows()) {
			foreach ($ctrls as $c) {
				$this->ctrl->delete_cards($c->id, $wiegand);
			}
			return true;
		} else {
			return null;
		}
	}

	/**
	 * Отправляет все карты (частями максимум 10 карт за раз) в контроллер,
	 * предварительно получив список карт людей, принадлежащих организации определенного контроллера
	 *
	 * @param int $ctrl_id ID контроллера
	 * @return int
	 */
	public function add_all_cards_to_controller($ctrl_id)
	{
		$this->load->model('ac/ctrl_model', 'ctrl');

		$this->db->select('cards.wiegand AS "wiegand"');
		$this->db->where('controllers.id', $ctrl_id);
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
				$counter += $this->ctrl->add_cards($ctrl_id, $data);
				$data = [];
			} elseif ($i == ($count - 1)) {
				$counter += $this->ctrl->add_cards($ctrl_id, $data);
			}
		}

		return $counter;
	}
}

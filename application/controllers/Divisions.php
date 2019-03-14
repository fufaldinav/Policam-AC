<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Divisions
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 */
class Divisions extends CI_Controller
{
	/**
	 * @var int $user_id
	 */
	private $user_id;

	/**
	 * @var mixed[] $orgs
	 */
	private $orgs;

	/**
	 * @var mixed[] $first_org
	 */
	private $first_org;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (! $this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$this->load->model('ac/div_model', 'div');
		$this->load->model('ac/org_model', 'org');
		$this->load->model('ac/person_model', 'person');

		$this->user_id = $this->ion_auth->user()->row()->id;
		$this->orgs = $this->org->get_all($this->user_id); //TODO
		$this->first_org = array_shift($this->orgs); //TODO
	}

	/**
	 * Получает подразделения текущей организации
	 */
	public function get_all()
	{
		$orgs = $this->org->get_all($this->user_id);

		$divs = [];
		foreach ($orgs as $org) {
			$divs = array_merge($divs, $this->div->get_list($org->id));
		}

		header('Content-Type: application/json');

		echo json_encode($divs);
	}

	/**
	 * Добавляет новое подразделение
	 */
	public function add()
	{
		if (! $this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$div = json_decode($this->input->post('div'));

		$div_id = $this->div->add($div);

		header('Content-Type: application/json');

		echo json_encode(
			$this->div->get($div_id)
		);
	}

	/**
	 * Удаляет подразделение
	 *
	 * @param int $div_id ID подразделения
	 */
	public function delete(int $div_id)
	{
		if (! $this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		//Получаем всех людей в удаляемом подразделении
		$persons = $this->person->get_all($div_id);

		//"Пустое" подразделение
		$new_div = $this->div->get_list($this->first_org->id, 0);
		$new_div = array_shift($new_div);

		//Переносим полученных людей в "пустое" подразделение
		//TODO проверят наличие людей в других подразделениях и тогда не добавлять в пустое
		foreach ($persons as $person) {
			$this->div->add_persons([$person->id], $new_div->id);
		}

		echo $this->div->delete($div_id);
	}
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Divisions
 * @property Div_model $div
 * @property Org_model $org
 */
class Divisions extends CI_Controller
{
	/**
	* @var int $user_id
	*/
	private $user_id;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$this->load->model('ac/div_model', 'div');
		$this->load->model('ac/org_model', 'org');

		$this->user_id = $this->ion_auth->user()->row()->id;
	}

	/**
	 * Получение информации о подразделении
	 */
	public function get_all()
	{
		$orgs = $this->org->get_all($this->user_id);

		$divs = [];
		foreach ($orgs as $org) {
			$divs = array_merge($divs, $this->div->get_all($org->id));
		}

		header('Content-Type: application/json');

		echo json_encode($divs);
	}

	/**
	 * Сохранение нового подразделения
	 */
	public function add()
	{
		if (!$this->ion_auth->in_group(2)) {
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
	 * Удаление подразделения
	 *
	 * @param int $div_id ID подразделения
	 */
	public function delete(int $div_id)
	{
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		echo $this->div->delete($div_id);
	}
}
